# Laravel Setup Guide for Resume Matching System

## Prerequisites

Ensure the following dependencies are installed on your system:

- PHP 8+
- Laravel 10+
- Composer
- MySQL/PostgreSQL
- OpenAI API Key

---

## Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/resume-matcher.git
cd resume-matcher
```

## Step 2: Install Dependencies

```bash
composer install
```

## Step 3: Configure Environment Variables

Copy the `.env.example` file to `.env` and update the required fields:

```bash
cp .env.example .env
```

Edit `.env` to configure the database and OpenAI API key:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

OPENAI_API_KEY=your_openai_api_key
```

## Step 4: Generate Application Key

```bash
php artisan key:generate
```

## Step 5: Run Database Migrations

```bash
php artisan migrate
```

## Step 6: Start Laravel Development Server

```bash
php artisan serve
```

This will start the server at `http://127.0.0.1:8000`.

---

## API Endpoints

### Upload Resumes & Process Matching

**Endpoint:**

```http
POST /upload-resumes
```

**Request Body (Form Data):**

```json
{
  "job_description": "Full Stack Developer with Laravel and Vue.js experience.",
  "resumes": ["file1.pdf", "file2.pdf"]
}
```

**Response Example:**

```json
{
  "ranked_resumes": [
    {
      "name": "John Doe",
      "matchScore": 85,
      "missingSkills": ["AWS", "Docker"],
      "justification": "Good match but lacks cloud experience."
    },
    {
      "name": "Jane Smith",
      "matchScore": 78,
      "missingSkills": ["Vue.js"],
      "justification": "Strong backend skills but needs frontend expertise."
    }
  ]
}
```

---

## Troubleshooting

### cURL SSL Certificate Issue (Windows)

If you encounter an SSL error when making API requests, update `php.ini`:

1. Locate `php.ini` file (`php -i | grep php.ini` to find the path).
2. Uncomment or add the following line:
   ```ini
   curl.cainfo="C:\path\to\cacert.pem"
   ```
3. Download `cacert.pem` from [cURL's website](https://curl.se/ca/cacert.pem) and place it in the specified path.
4. Restart the server.

### OpenAI Quota Exceeded

If OpenAI returns a 429 error, check your API usage at [OpenAI Dashboard](https://platform.openai.com/).

---

## Next Steps

- Implement authentication for better security
- Optimize AI prompts for better matching
- Deploy the system to a production environment

Happy coding! 🚀
