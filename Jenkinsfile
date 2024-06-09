pipeline {
    agent { label '!windows' }
    stages {
        stage('Build') {
            steps {
                sh 'echo "Building..."'
            }
        }
        stage('PHPUnit') {
            steps {
                dir('tests') {
                    sh 'composer update'
                    sh 'php vendor/bin/phpunit tests'
                }
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
