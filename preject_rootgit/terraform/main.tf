terraform {
  required_providers {
    kubernetes = {
      source  = "hashicorp/kubernetes"
      version = "~> 2.25"
    }
  }
}

provider "kubernetes" {
  config_path = pathexpand("~/.kube/config")
}

variable "app_name" {
  default = "preject-rootgit"
}

variable "image" {
  default = "chi7d/preject-rootgit:latest"
}

variable "replicas" {
  default = 2
}

variable "db_name" {
  default = "mon_projet"
}

variable "db_user" {
  default = "user"
}

variable "db_password" {
  default   = "password"
  sensitive = true
}

variable "db_root_password" {
  default   = "rootpassword"
  sensitive = true
}

resource "kubernetes_secret" "db_secret" {
  metadata {
    name = "db-secret"
  }
  data = {
    "db-user"   = var.db_user
    "db-pass"   = var.db_password
    "root-pass" = var.db_root_password
  }
}

resource "kubernetes_persistent_volume_claim" "mysql_pvc" {
  metadata {
    name = "mysql-pvc"
  }
  spec {
    access_modes = ["ReadWriteOnce"]
    resources {
      requests = {
        storage = "2Gi"
      }
    }
  }
}

resource "kubernetes_deployment" "app" {
  metadata {
    name = var.app_name
    labels = {
      app = var.app_name
    }
  }
  spec {
    replicas = var.replicas
    selector {
      match_labels = {
        app = var.app_name
      }
    }
    strategy {
      type = "RollingUpdate"
      rolling_update {
        max_surge       = "1"
        max_unavailable = "0"
      }
    }
    template {
      metadata {
        labels = {
          app = var.app_name
        }
      }
      spec {
        container {
          name  = "php-app"
          image = var.image
          port {
            container_port = 80
          }
          env {
            name  = "DB_HOST"
            value = "mysql-service"
          }
          env {
            name  = "DB_NAME"
            value = var.db_name
          }
          env {
            name = "DB_USER"
            value_from {
              secret_key_ref {
                name = kubernetes_secret.db_secret.metadata[0].name
                key  = "db-user"
              }
            }
          }
          env {
            name = "DB_PASS"
            value_from {
              secret_key_ref {
                name = kubernetes_secret.db_secret.metadata[0].name
                key  = "db-pass"
              }
            }
          }
          resources {
            requests = {
              cpu    = "100m"
              memory = "128Mi"
            }
            limits = {
              cpu    = "500m"
              memory = "256Mi"
            }
          }
          liveness_probe {
            http_get {
              path = "/index.php"
              port = 80
            }
            initial_delay_seconds = 15
            period_seconds        = 20
          }
        }
      }
    }
  }
}

resource "kubernetes_deployment" "mysql" {
  metadata {
    name = "mysql"
    labels = {
      app = "mysql"
    }
  }
  spec {
    replicas = 1
    selector {
      match_labels = {
        app = "mysql"
      }
    }
    template {
      metadata {
        labels = {
          app = "mysql"
        }
      }
      spec {
        container {
          name  = "mysql"
          image = "mysql:8.0"
          port {
            container_port = 3306
          }
          env {
            name = "MYSQL_ROOT_PASSWORD"
            value_from {
              secret_key_ref {
                name = kubernetes_secret.db_secret.metadata[0].name
                key  = "root-pass"
              }
            }
          }
          env {
            name  = "MYSQL_DATABASE"
            value = var.db_name
          }
          env {
            name = "MYSQL_USER"
            value_from {
              secret_key_ref {
                name = kubernetes_secret.db_secret.metadata[0].name
                key  = "db-user"
              }
            }
          }
          env {
            name = "MYSQL_PASSWORD"
            value_from {
              secret_key_ref {
                name = kubernetes_secret.db_secret.metadata[0].name
                key  = "db-pass"
              }
            }
          }
          volume_mount {
            name       = "mysql-storage"
            mount_path = "/var/lib/mysql"
          }
        }
        volume {
          name = "mysql-storage"
          persistent_volume_claim {
            claim_name = kubernetes_persistent_volume_claim.mysql_pvc.metadata[0].name
          }
        }
      }
    }
  }
}

resource "kubernetes_service" "app_service" {
  metadata {
    name = "preject-service"
  }
  spec {
    selector = {
      app = var.app_name
    }
    port {
      port        = 80
      target_port = 80
      node_port   = 30080
    }
    type = "NodePort"
  }
}

resource "kubernetes_service" "mysql_service" {
  metadata {
    name = "mysql-service"
  }
  spec {
    selector = {
      app = "mysql"
    }
    port {
      port        = 3306
      target_port = 3306
    }
    type = "ClusterIP"
  }
}

output "app_service" {
  value = "Application accessible sur NodePort 30080"
}

output "app_replicas" {
  value = "${var.replicas} replicas deployes"
}

output "mysql_service" {
  value = "MySQL accessible sur mysql-service:3306"
}
