pipeline {
    agent { label '!windows' }
    stages {
        stage('Build') {
            steps {
                sh 'echo "Building..."'
            }
        }
        stage('Install Dependencies') {
            steps {
                sh 'echo "Current directory: $(pwd)"'
                sh 'ls -la'
                sh 'composer install'
            }
        }
        stage('PHPUnit') {
            steps {
                sh 'echo "Running PHPUnit tests..."'
                sh './vendor/bin/phpunit'
            }
        }
        stage('SonarQube') {
            steps {
                script { scannerHome = tool 'scanner-sonar' }
                withSonarQubeEnv('SonarQube') {
                    sh "${scannerHome}/bin/sonar-scanner \
                        -Dsonar.projectKey=ows-sonarqube"
                }
            }
        }
    }
}
