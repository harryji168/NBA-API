
# NBA API Requester Project

This project serves as a demonstration of constructing a straightforward PHP API client using object-oriented programming principles. The client is designed to retrieve NBA games data from the RapidAPI platform. 

## The live project can be accessed at: [nba.harryji.com.](https://nba.harryji.com)

![Screenshot from 2023-07-03 13-22-22](https://github.com/harryji168/NBA-API/assets/21187699/9be074a1-f202-4ef2-9946-2695273afb42)

## Project Structure
The structure of the project adheres to the Laravel file hierarchy. 
```
NBA_API_project/
├── app/
│   ├── Helpers/
│   │   ├── DataFilters.php
│   │   ├── Dates.php
│   │   ├── Storage.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   ├── Services/
│   │   ├── NbaApiRequester.php
│   │   ├── NbaGamesService.php
│   │   ├── NbaSeasonsService.php
├── public/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   ├── font-awesome.css
│   ├── js/
│   │   ├── jquery.js
│   ├── image/
│   │   ├── nba.png
│   ├── webfonts/
│   │   ├── fa-brands-400.eot
│   ├── ajax-handler.php
│   ├── index.php
├── resources/
│   ├── views/
│   │   ├── nba-game-table.php
│   │   ├── forms/
│   │   │   ├── ShowEntriesForm.php
│   │   │   ├── SearchForm.php
│   │   │   ├── PaginationForm.php
│   │   │   ├── EntriesPerPageForm.php
├── storage/
│   ├── cache/
│   │   ├── cache_6edb04f1190a0af6e485a4068df76856.html
│   ├── json/
│   │   ├── seasons.json
│   ├── logs/
│   │   ├── nba_api.log
├── tests/
│   ├── unit/
│   │   ├── Helpers/
│   │   │   ├── DataFiltersTest.php
│   │   │   ├── DatesTest.php
│   │   │   ├── StorageTest.php
│   │   ├── Providers/
│   │   │   ├── AppServiceProviderTest.php
│   │   ├── Services/
│   │   │   ├── NbaApiRequesterTest.php
│   │   │   ├── NbaGamesServiceTest.php
│   │   │   ├── NbaSeasonsServiceTest.php
├── vendor/
├── docker-compose.yml
├── Dockerfile
├── composer.json
├── nginx.conf
├── README.md
├── .env.sample
└── .env (to be created by user)
```

## Setup

### Docker Compose Setup

1. Install Docker and Docker Compose on your system.
2. Clone this repository to your local system.
3. Navigate to the project root directory.
4. Run `docker-compose up -d` to build the images and start the containers.

## Usage

### Docker Compose Usage

The services can be accessed on the following ports on your host machine:

- PHP service: `9000`
- Nginx service: `8061` (HTTP), `44361` (HTTPS)
- MySQL service: `33639`

To execute commands inside a Docker container, you can use `docker exec`. For example, to execute PHPUnit tests, you can use:

```bash
docker exec -it dcindesign ./vendor/bin/phpunit tests/unit/Services/NbaApiRequesterTest.php
```

## Running Tests

### Docker Tests

From within the Docker container, run `./vendor/bin/phpunit tests/unit/Services/NbaApiRequesterTest.php` to execute the unit tests.

## License

This project is licensed under the terms of the MIT license.
 

# NBA API Requester Project - database solution

This project serves as a demonstration of constructing a straightforward PHP API client using object-oriented programming principles. The client is designed to retrieve NBA games data from the RapidAPI platform. 

## The live project can be accessed at: [nba.harryji.com.](https://nba.harryji.com)

![Screenshot from 2023-07-03 13-22-22](https://github.com/harryji168/NBA-API/assets/21187699/114f10ed-7c45-4149-8dec-9725ce9cdc76)

## Project Structure
The structure of the project adheres to the Laravel file hierarchy. 
```
NBA_API_project/
├── app/
│   ├── Helpers/
│   │   ├── DataFilters.php
│   │   ├── Dates.php
│   │   ├── Storage.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   ├── Services/
│   │   ├── NbaApiRequester.php
│   │   ├── NbaGamesService.php
│   │   ├── NbaSeasonsService.php
│   │   ├── GameDataProcessor.php  <-- Added
├── config/
│   ├── Database.php  <-- Added
├── database/
│   ├── migrations/
│   │   ├── 2023_07_03_000001_create_teams_table.php  <-- Added
│   ├── Migration.php  <-- Added
│   ├── MigrationManager.php  <-- Added
├── public/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   ├── font-awesome.css
│   ├── js/
│   │   ├── jquery.js
│   ├── image/
│   │   ├── nba.png
│   ├── webfonts/
│   │   ├── fa-brands-400.eot
│   ├── ajax-handler.php
│   ├── index.php
├── resources/
│   ├── views/
│   │   ├── nba-game-table.php
│   │   ├── forms/
│   │   │   ├── ShowEntriesForm.php
│   │   │   ├── SearchForm.php
│   │   │   ├── PaginationForm.php
│   │   │   ├── EntriesPerPageForm.php
├── storage/
│   ├── cache/
│   │   ├── cache_6edb04f1190a0af6e485a4068df76856.html
│   ├── json/
│   │   ├── seasons.json
│   ├── logs/
│   │   ├── nba_api.log
├── tests/
│   ├── unit/
│   │   ├── Helpers/
│   │   │   ├── DataFiltersTest.php
│   │   │   ├── DatesTest.php
│   │   │   ├── StorageTest.php
│   │   ├── Providers/
│   │   │   ├── AppServiceProviderTest.php
│   │   ├── Services/
│   │   │   ├── NbaApiRequesterTest.php
│   │   │   ├── NbaGamesServiceTest.php
│   │   │   ├── NbaSeasonsServiceTest.php
├── vendor/
├── docker-compose.yml
├── Dockerfile
├── composer.json
├── nginx.conf
├── README.md
├── .env.sample
└── .env (to be created by user)
```

## Documentation 
For detailed insights and usage of our project, refer to our comprehensive documentation. This includes a guide on how to use various features, examples, and tips for best practices.

Visit our documentation page at: https://harryji168.github.io/NBA-API/v2

We regularly update our documentation to ensure it reflects the most recent changes and updates in the NBA-API project. If you have any suggestions or find any discrepancies, feel free to raise an issue on the GitHub repository.


## Setup

### Docker Compose Setup

1. Install Docker and Docker Compose on your system.
2. Clone this repository to your local system.
3. Navigate to the project root directory.
4. Run `docker-compose up -d` to build the images and start the containers.

## Usage

### Docker Compose Usage

The services can be accessed on the following ports on your host machine:

- PHP service: `9000`
- Nginx service: `8061` (HTTP), `44361` (HTTPS)
- MySQL service: `33639`

To execute commands inside a Docker container, you can use `docker exec`. For example, to execute PHPUnit tests, you can use:

```bash
docker exec -it dcindesign ./vendor/bin/phpunit tests/unit/Services/NbaApiRequesterTest.php
```

## Running Tests

### Docker Tests

From within the Docker container, run `./vendor/bin/phpunit tests/unit/Services/NbaApiRequesterTest.php` to execute the unit tests.

# CI/CD

## Google Cloud Platform (GCP)

- Google Cloud Build
First, create a cloudbuild.yaml configuration file in your project root directory. This file instructs Cloud Build on how to build and deploy your application. In this file, you'd specify the steps to install dependencies, run tests, build your project, and deploy to App Engine.
  
- Google Cloud Source Repositories
Assuming your source code is already in Google Cloud Source Repositories, proceed to the next step. If not, you can add your code to Google Cloud Source Repositories by following the steps in the official documentation.

- Cloud Build Trigger
    In the GCP Console, navigate to Cloud Build and then click on Triggers. Here, you'd set up a new trigger. When creating a new trigger:

    Choose your repository from the list.
    Specify the branch for which the trigger should run (for example, master or main).
    Specify the location of the cloudbuild.yaml file in the build configuration.
- Test Your Pipeline
Commit a change to your repository or manually run the trigger to see if everything works as expected.


## AWS Cloud

Setup Source Control (CodeCommit/GitHub etc.): Create a repository in AWS CodeCommit or you could use GitHub and commit your NBA project code to the repository. If you're using a docker-compose.yml, make sure it is committed.

Setup Build Stage (CodeBuild): Create a build project in AWS CodeBuild. Here, you define how CodeBuild will run a build. This includes information like where to get the source code, which build environment to use, build commands, and where to store the build output.

You also need a buildspec.yml file. The buildspec file provides the instructions for a build. Below is a simplified example of a buildspec.yml:

```
version: 0.2

phases:
  pre_build:
    commands:
      - echo Logging in to Amazon ECR...
      - $(aws ecr get-login --no-include-email --region region-name)
  build:
    commands:
      - echo Build started on `date`
      - echo Building the Docker image...
      - docker build -t sample-nba-project .
      - docker tag sample-nba-project:latest repository-uri:latest
  post_build:
    commands:
      - echo Build completed on `date`
      - echo Pushing the Docker image...
      - docker push repository-uri:latest
```
Please replace region-name and repository-uri with your actual AWS region and ECR repository URI.

Setup Deployment Stage (CodeDeploy/ECS/EKS): After you have successfully built your Docker image, you could use AWS services like CodeDeploy for application deployment, or services like ECS or EKS if you're using Docker containers. For this, you will need to define a deployment specification file (appspec.yml for CodeDeploy or ECS task definition for ECS).

Create CodePipeline: Finally, you can create a pipeline in AWS CodePipeline and add the stages you have created. This typically includes a source stage (like CodeCommit or GitHub), a build stage (like CodeBuild), and a deploy stage (like CodeDeploy or ECS).

As you set up your CI/CD pipeline, remember to set up necessary IAM roles and permissions for the services to interact with each other. The exact setup might be different depending on the specifics of NBA project and the AWS services you choose to use.

## Azure Cloud

Setting Up The Pipeline
To setup the pipeline:

Go to the Azure DevOps portal.
Click on Pipelines.
Click on New pipeline.
Select where your code is located (GitHub, Azure Repos Git, etc.)
You might need to authorize Azure Pipelines to access your code.
Configure your pipeline - you can select the azure-pipelines.yml file.
Setting Up The Deployment
Depending on where you want to deploy your application, you might have to add tasks to your azure-pipelines.yml file. For instance, if you want to deploy to Azure App Service, you can add the Azure App Service Deploy task.

This would look something like:

```
- task: AzureRmWebAppDeployment@4
  inputs:
    ConnectionType: 'AzureRM'
    azureSubscription: 'your-azure-subscription'
    appType: 'webApp'
    WebAppName: 'your-app-name'
    deployToSlotOrASE: true
    ResourceGroupName: 'your-resource-group'
    packageForLinux: '$(build.artifactstagingdirectory)/*.zip'
```
Note that the above is just a sample, you should replace 'your-azure-subscription', 'your-app-name', and 'your-resource-group' with your actual Azure Subscription, App name, and Resource Group respectively.

Run the Pipeline
After setting up the pipeline and adding the deployment step, you can now run the pipeline. Every time you push to the master branch, it will trigger the pipeline, build your code, and deploy it.

For PHP application, you might need to add specific build steps to handle your application like composer install or handling any database migrations. You can add these in the scripts part of the YAML file.

Also, remember to handle the .env file or environment variables, you can use Azure's Application Settings for handling environment variables.

Lastly, remember to replace any placeholders with the actual values applicable to your project.


## Alibaba Cloud

Source Control: You could use Alibaba's CodeCommit or any other third-party version control system like GitHub to host your source code. You'd need to commit your NBA project code to this repository. If you're using a docker-compose.yml, make sure it's included in the repository.

Build Stage (Container Registry): Alibaba's Container Registry service can be used to automate the build of Docker images from your source code whenever a new commit is made. Container Registry supports docker-compose for multi-container applications.

Deploy Stage (ECS): Once the image is built, you can deploy it to Alibaba's Elastic Compute Service (ECS). For deploying, you'd need to define an ECS template. You can define rules to pull the latest Docker image from the Container Registry and deploy it.

Pipeline Creation (CodePipeline): Finally, you can create a pipeline in Alibaba's CodePipeline. Here, you could configure the source control stage (CodeCommit or GitHub), the build stage (Container Registry), and the deployment stage (ECS). CodePipeline would orchestrate the entire process, building and deploying your application whenever there is a change in the source code.

Here's a high-level example of how your pipeline configuration might look:

```
# pipeline.yml

pipeline:
- name: Source Stage
    category: Source
    provider: CodeCommit
    version: 1
    configuration:
    RepoName: nba-project
    BranchName: master
- name: Build Stage
    category: Build
    provider: ContainerRegistry
    version: 1
    configuration:
    ImageName: nba-project
    DockerfilePath: Dockerfile
- name: Deploy Stage
    category: Deploy
    provider: ECS
    version: 1
    configuration:
    AppName: nba-project
    EnvironmentName: Production
```
This file defines three stages in the pipeline: Source, Build, and Deploy. Each stage has a category, provider, version, and configuration section.

Note: You'll need to replace all placeholder values (RepoName, BranchName, ImageName, DockerfilePath, AppName, EnvironmentName) with the actual values for your project.

Additionally, you'll need to ensure the correct permissions are set up for each service to interact with each other.

This is a simplified outline for a CI/CD pipeline. Depending on your project, you might need to include additional stages (like testing), set up notifications for build failures, or configure advanced deployment strategies.

## Heroku

1. Connect Your GitHub Repository to Heroku:

    From your Heroku dashboard, navigate to the "Deploy" tab in your app's dashboard.
    Choose GitHub as the deployment method.
    Search for your repository and click "Connect".

2. Enable Review Apps (optional):

    Review apps are a Heroku feature that deploys code from GitHub pull requests as disposable apps. Each pull request can be reviewed in a live application environment.

    Click on "Enable Review Apps…" button.
    Check "Create new review apps for new pull requests automatically".
    Select a pipeline to use.
    Click "Enable".

3. Setup Automatic Deploys:

    Still under the "Deploy" tab, navigate to the "Automatic deploys" section.
    Choose a branch that you want to deploy automatically whenever it's pushed to. This is typically your main or production branch.
    Click "Enable Automatic Deploys".

4. Setup Manual Promotion (optional):

    For a more controlled deployment, you can require manual promotion of a successful build on a given branch (like a staging branch) to production.

    Go to the "Pipeline" tab on your dashboard.
    For a successful build in the staging app, click "Promote to production".

5. Configure Environment Variables:

    Heroku allows you to add environment variables (like those in your .env file). These can be added in the "Settings" tab of your app in Heroku, under "Config Vars".

6. Add a Procfile to Your Project:

    Heroku uses a Procfile to understand how to start your application. It should be located at the root of your repository. For a typical PHP web application, your Procfile might look something like this:

    vbnet
    ```
    web: vendor/bin/heroku-php-apache2 public/
    ```
    This line instructs Heroku to use the Apache web server with PHP to serve the files located in the public directory.


## DigitalOcean

1. Setup Your GitHub Repository:
Ensure your NBA project code is in a GitHub repository.

2. Create GitHub Actions Workflow:
In your repository, create a new file under .github/workflows, for example, ci-cd.yml. This file will contain the configuration for your CI/CD pipeline.

Here's a simple example for a PHP application that uses Docker:

```
name: CI/CD Pipeline

on: [push]

jobs:
  build:
    runs-on: ubuntu-latest

    steps:
    - name: Checkout code
      uses: actions/checkout@v2

    - name: Build and push Docker image
      uses: docker/build-push-action@v2
      with:
        context: .
        push: true
        tags: your-dockerhub-username/nba-project:${{ github.sha }}

  deploy:
    needs: build
    runs-on: ubuntu-latest
    steps:
    - name: Execute remote ssh commands to deploy
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.DROPLET_IP }}
        username: ${{ secrets.DROPLET_USER }}
        key: ${{ secrets.DROPLET_SSHKEY }}
        script: |
          docker pull your-dockerhub-username/nba-project:${{ github.sha }}
          docker stop nba-container || true
          docker rm nba-container || true
          docker run -d --name nba-container -p 80:80 your-dockerhub-username/nba-project:${{ github.sha }}
```
In this example, it will build a Docker image from your application and push it to Docker Hub whenever you push changes to your repository. It will then SSH into your DigitalOcean Droplet and run the Docker container.

Make sure to replace your-dockerhub-username with your actual Docker Hub username, and nba-project with your actual project name.

3. Configure Secrets:
You will need to add the following secrets to your GitHub repository:

DROPLET_IP: The IP address of your DigitalOcean droplet.
DROPLET_USER: The username to SSH as (typically root).
DROPLET_SSHKEY: The private SSH key to use.
You can add these under "Settings" -> "Secrets" in your GitHub repository.

With this setup, every time you push to your GitHub repository, it will automatically build your Docker image and deploy it to your DigitalOcean droplet.

## Vercel
1. Connect your GitHub repository to Vercel:

    Sign up for a Vercel account and go to your Vercel dashboard.
    Click on "Import Project".
    Then choose "From Git Repository".
    On the "Import Git Repository" page, connect with your GitHub account.
    Choose the repository that has your NBA project and click "Import".

2. Configure Your Project:

    Choose the root directory of your project if your project is not in the root of the repository.
    Define the Build and Output settings for your project.
    Add necessary environment variables as needed by your project.

3. Deploy Your Project:

    Vercel will create a deployment for your project and provide you with a deployment URL.
    You can check the status of the deployment on your Vercel dashboard.

4. Set up Automatic Deployments:

    With Vercel’s Git integration, every push to your linked repository will automatically create a new deployment of your NBA project.

5. Enable Preview Deployments:

    For every pull request made in your GitHub repository, Vercel automatically creates a preview deployment so you can test changes made in the pull request.
    Once the pull request is merged, a production deployment is triggered.

6. Configure Custom Domains (Optional):

    You can assign custom domains to your Vercel projects. To add a domain, navigate to your project settings and then click on the "Domains" tab.
    This sets up a basic CI/CD pipeline for your NBA project on Vercel. The actual setup may vary depending on the specifics of your project.

Remember, the vercel.json file allows you to configure your deployment, including routes, headers, redirects, rewrites, and more. Be sure to utilize it as per your project requirements.


## Cpanel

1. Create a Git Repository in cPanel:

    From the cPanel dashboard, select 'Git Version Control' under the 'Files' section.
    Click on 'Create' and select 'Clone a Repository'.
    Provide the clone URL of your repository, and specify the directory you'd like to deploy to.
    Click 'Create' to create the repository.

2. Set Up a Post-Receive Hook:

    Post-receive hooks are scripts that run automatically when changes are pushed to your repository.

    Access the cPanel file manager and navigate to the directory of the Git repository you created.
    Inside the repository, navigate to the hooks directory.
    Create a new file named post-receive.
    Edit the file and add a script that will deploy your application. This may vary based on your application, but it may look something like this for a PHP application:
bash
```
#!/bin/sh
git --work-tree=/path/to/your/deployment/directory --git-dir=/path/to/your/git/repository checkout -f
```

Save and close the file, then change its permissions to make it executable (chmod +x post-receive).

3. Push Changes to Your Repository:

    Now, whenever you push changes to your repository, the post-receive hook should automatically deploy your application to the specified directory.

This setup has limitations. It doesn't handle complex deployment steps such as compiling assets, migrating databases, or managing environment variables. For more complex applications, a more robust hosting solution that supports modern CI/CD practices would be beneficial.


## License

This project is licensed under the terms of the MIT license.
 
