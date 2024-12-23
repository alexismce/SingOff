## Project Overview
The CalFire Project is a website designed to manage operations for the CalFire team, including user authentication, form submissions, PDF generation, and more.

## Key Components
- **Languages**: Primarily PHP (80%), JavaScript (15%), Hack (2.4%), Shell (1.6%), CSS (1%)
- **Features**:
  - User authentication
  - Form data handling
  - PDF generation using TCPDF
  - Signature capture and processing
  - Database management

## Requirements
- PHP ^7.4 || ^8.0 || ^8.2
- Composer
- Node.js >= 18.0.0
- MySQL

## Installation Instructions
1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/CalFire.git
   cd CalFire
   ```
2. **Install PHP dependencies**:
   ```bash
   composer install
   ```
3. **Install Node.js dependencies**:
   ```bash
   npm install
   ```
4. **Set up environment variables**:
   Create a `.env` file in the root directory with the following content:
   ```dotenv
   DB_SERVERNAME=your-server-name
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   DB_NAME=your-database
   DB_PORT=3306
   DB_FLAG=MYSQLI_CLIENT_SSL
   APPINSIGHTS_INSTRUMENTATIONKEY=your-instrumentation-key
   ```
   Replace placeholders with actual values.

## Usage
- **Start the PHP server**:
  ```bash
  php -S localhost:8000
  ```
- **Run tests**:
  ```bash
  composer test
  ```

## Deployment
- **GitHub Actions**: Configured for CI/CD with the `main_calfire.yml` workflow.
- **Azure Web App**: Deployed to Azure Web App; update the `main_calfire.yml` with your Azure Web App name and resource group.

## Contribution
- Contributions are welcome via pull requests or issues.

## Authors
- Alexis Felix - afelix@radiomobile.com

## License
- Licensed under the ISC License.

This analysis provides an overview, setup instructions, and deployment details for your project. If there are specific parts of the project you need further analysis on, please let me know!
