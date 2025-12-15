PDF & CSV → Blog Post Feature

You are a senior software engineer working on this application.
You must follow the rules below exactly.
Do not invent alternative behavior.
Do not apply heuristics.
Do not “improve” the product logic.

---

### CORE PRINCIPLE (NON-NEGOTIABLE)

**Each uploaded file creates exactly one new blog post.**

* One file → one post
* No matching against existing posts
* No merging
* No guessing

This rule overrides all others.

---

### UPLOAD BEHAVIOR

When a user uploads a file (PDF or CSV):

1. Validate file type and size
2. Store the original file
3. Create a new blog post immediately
4. Associate the file with that blog post
5. Extract text from the file asynchronously
6. Populate the blog post content with extracted text
7. Mark the post as `draft`

Uploads must succeed even if text extraction fails.

---

### SUPPORTED FILE TYPES

* PDF
* CSV

Bulk upload:

* Multiple files → multiple blog posts
* Process each file independently

---

### BLOG POST CREATION RULES

For every uploaded file:

* `title`

    * Default to filename (without extension)
* `content`

    * Extracted text (normalized, readable)
* `source_file_id`

    * Required
* `author_id`

    * Authenticated user → user_id
    * Guest → null
* `status`

    * `draft`

CSV files:

* Convert rows into structured readable content (tables or sections)
* Do not merge CSV files

---

### FILE ↔ POST RELATIONSHIP

* A file belongs to exactly one blog post
* A blog post has exactly one source file (optional only for manually created posts)
* This relationship must be enforced at the data level

---

### EDITING RULES

* Users may edit blog post content freely
* Original extracted text must be preserved separately
* Editing content must not modify the original file
* Regeneration from file is not automatic

---

### ACCESS CONTROL

Guest users:

* Posts are session-scoped
* Auto-delete after TTL
* No ownership migration

Authenticated users:

* Posts are permanent
* Owned by the user
* Fully editable

Do not auto-claim guest posts after login.

---

### DOWNLOAD & EXPORT RULES

If a blog post has a source file:

* Primary download → original file
* Secondary action → “Export edited content as PDF”

If a blog post has **no source file**:

* Show **“Download as PDF”**
* Generate PDF from blog content

PDF generation:

* Cached
* Deterministic
* No regeneration unless content changes

---

### FORBIDDEN BEHAVIOR

You must not:

* Match extracted text to existing posts
* Link files to multiple posts
* Create posts without files during upload
* Merge multiple files into one post
* Block uploads due to parsing failures
* Modify content without explicit user action

---

### FAILURE HANDLING

* Parsing failures must be logged
* Parsing failures must not block post creation
* UI must show parsing status

No silent failures.

---

### DEFINITION OF DONE

The feature is complete only if:

* File upload always creates a new blog post
* Blog content comes from extracted file text
* Original file is always downloadable
* Manual posts still export to PDF
* Access control is enforced
* Behavior is deterministic

---

### OUTPUT EXPECTATIONS

When implementing:

* Prefer clarity over abstraction
* Prefer explicit state over magic
* Prefer correctness over convenience


## QUEUE & BACKGROUND PROCESSING (MANDATORY)

All file-related processing **must be asynchronous** and handled via a queue.

### Queue system

* Use **Redis** as the queue backend
* Use **Laravel Queues**
* Use **Laravel Horizon** for monitoring, retries, and throughput control

No file parsing or heavy processing is allowed in HTTP request lifecycle.

---

### What MUST be queued

For every uploaded file:

* PDF text extraction
* CSV normalization
* Content cleaning
* Blog post content population
* PDF export generation
* Any re-processing or regeneration tasks

Only validation and persistence may run synchronously.

---

### Queue Job Design

Each uploaded file must enqueue jobs in this order:

1. `CreateBlogPostFromFile`

    * Creates blog post in `draft`
    * Associates file
    * Sets parsing state = `pending`

2. `ExtractFileText`

    * PDF: page-by-page extraction
    * CSV: structured text normalization
    * Stores raw extracted text
    * Updates parsing state = `extracted`

3. `PopulateBlogContent`

    * Cleans and formats extracted text
    * Writes to blog post content
    * Updates parsing state = `completed`

Jobs must be:

* Idempotent
* Retry-safe
* Independently executable

No job may assume previous jobs succeeded silently.

---

### Failure Handling (Strict)

* Failed jobs must:

    * Be retried according to Horizon config
    * Mark blog post parsing state = `failed` after max retries
* Upload must NEVER be rolled back due to queue failure
* User must see parsing status and failure reason

No silent drops.

---

### Horizon Requirements

* Horizon must be enabled in all non-local environments
* Separate queues:

    * `uploads`
    * `parsing`
    * `exports`
* Concurrency limits must be configurable
* Long-running jobs must be isolated from short jobs

---

### UI / State Expectations

Each blog post must expose:

* Parsing state:

    * `pending`
    * `processing`
    * `completed`
    * `failed`
* Last job timestamp
* Retry availability (manual trigger)

---

### Forbidden Behavior (Queue-related)

You must not:

* Parse files synchronously
* Use inline workers
* Bypass Redis
* Use database queues
* Execute parsing inside controllers
* Hide queue failures

---

### Definition of Done (Queue)

Feature is incomplete unless:

* All parsing runs via Redis queues
* Horizon shows active jobs
* Failed jobs are visible and retryable
* App remains responsive during heavy uploads
