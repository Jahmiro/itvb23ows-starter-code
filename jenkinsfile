pipeline {
    agent {label '!windows'}
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
        stage('SonarQube') {
            steps {
                script { scannerHome = tool 'SonarQube Scanner' }
                withSonarQubeEnv('SonarQube') {
                    sh "${scannerHome}/bin/sonar-scanner
                        -Dsonar.projectKey=sqp_21d3d89ae9208ebc2ad16122f211bc8e43880f82"
                }
            }
        }
    }
