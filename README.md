# Image Service 

## Table of Contents

- [Image Service](#image-service)
  - [Table of Contents](#table-of-contents)
  - [Introduction](#introduction)
  - [Prerequisites](#prerequisites)
  - [Getting Started](#getting-started)
    - [Cloning the Repository](#cloning-the-repository)
    - [Docker Setup](#docker-setup)
  - [Running Automated Tests](#running-automated-tests)
  - [Application Usage](#application-usage)
    - [Supported Modifiers](#supported-modifiers)
    - [Request Parameters](#request-parameters)
    - [Example Usage](#example-usage)
  - [CI Pipeline](#ci-pipeline)
  - [Conclusion](#conclusion)

## Introduction

The Image Service is a powerful application that allows you to process and modify images on the fly. It provides a RESTful API to apply various image modifiers, such as cropping and resizing, to images hosted on your server.

This documentation will guide you through the installation, setup, and usage of the Image Service, along with examples to help you get started.


## Prerequisites

Before getting started, make sure you have the following prerequisites installed on your system:

-   [Docker](https://www.docker.com/) version 20.0 or higher
-   [Git](https://git-scm.com/) version 2.0 or higher

## Getting Started

### Cloning the Repository

To get started, clone the Image Service repository from GitHub to your local machine:

```bash
git clone https://github.com/kanojiyadhaval/image-service
cd image-service
```

### Docker Setup

The Image Service is designed to run in a Docker container. Follow these steps to set up the application using Docker:

1. Build the Docker image using the provided docker-compose file:

```bash
docker-compose up -d --build
```

2. Stop the application by running :

```bash
docker-compose down --remove-orphans
```

The Image Service is now up and running, accessible at `http://localhost:80` in your web browser.

## Running Automated Tests

The Image Service comes with a suite of PHPUnit tests to ensure its functionality and stability. To run the automated tests, follow these steps:

1. Access php container and run PHPUnit tests:

```bash
docker-compose exec php composer test
```

## Application Usage

The Image Service accepts HTTP requests with parameters to apply various image modifiers. Here are the supported modifiers and how to use them:

### Supported Modifiers

1. Crop: Crops the image to a specified width and height.

2. Resize: Resizes the image to a specified width and height.

### Request Parameters

The Image Service accepts the following parameters in the HTTP request:

1. `image`: The name of the image file to modify. (Required)

2. `crop`: The crop dimensions in the format `widthxheight`. (Required)

3. `resize`: The resize dimensions in the format `widthxheight`. (Required)

### Example Usage

To crop an image named `image.png` to a size of 300x200, make the following HTTP request:

```
GET http://localhost/crop/image.png/crop=300x200
```

To resize the same image to a size of 800x600, make the following HTTP request:

```
GET http://localhost/resize/image.png/crop=300x200
```

The modified images will be available at the `images/modified` directory. And display automatically with follow url :

```
http://localhost/display_image.php?modifiedImage=<modified-image>.png
```

## CI Pipeline
The Image Service Application uses GitHub Actions for continuous integration. When you push changes to the main branch, the CI pipeline will automatically trigger and run the PHPUnit tests. The pipeline is defined in the .github/workflows/ci.yml file.

You can check the CI test results in the "Actions" tab of your GitHub repository. The pipeline will run tests for each push to the main branch and report the results.

## Conclusion

The Image Service provides a convenient way to modify images on the fly with its supported modifiers. It can be further customized to meet your specific requirements. Happy image processing! :)
