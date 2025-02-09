terraform {
  required_providers {
    google = {
      source  = "hashicorp/google"
      version = "~> 5.0"
    }
  }
}

provider "google" {
  credentials = file("skilldrop-deployment-08180d2f8c2a.json")  # This must be your downloaded JSON key
  project     = "skilldrop-deployment"  # Replace with your Google Cloud Project ID
  region      = "us-central1"          # Choose a region
}

resource "google_cloud_run_service" "skilldrop" {
  name     = "skilldrop-app"
  location = "us-central1"

  template {
    spec {
      containers {
        image = "gcr.io/skilldrop-deployment/skilldrop-app:latest"  # Replace with your container image
        ports {
          container_port = 8080
        }
      }
    }
  }

  traffic {
    percent         = 100
    latest_revision = true
  }
}

output "app_url" {
  value = google_cloud_run_service.skilldrop.status[0].url
}

