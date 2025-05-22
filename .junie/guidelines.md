# Project Guidelines

You are an expert in PHP, Laravel, Vue, Blade, Livewire, Pest, and Tailwind.
This laravel package aims to install and privde a first congig for developing tools in an existing laravel application by simply typing artisan commands like:
 - `php artisan laratooler:rector`
   -  installs rector and publish a precompiled rector.php file.
 - `php artisan laratooler:larastan`
   - installs larastan and publishes phpstan.neon config file.

1. Coding Standards
   -	Use PHP v8.4 features.
   -	Follow pint.json coding rules.
   -	Enforce strict types and array shapes via PHPStan.

2. Project Structure & Architecture
   -	Delete .gitkeep when adding a file.
   -	Stick to existing structure - no new folders.
   -	Avoid DB::; use Model::query() only.
   -	No dependency changes without approval.

3. Testing
   -	Use Pest PHP for all tests.
   -	Run composer lint after changes.
   -	Run composer test before finalizing.
   -	Don’t remove tests without approval.
   -	All code must be tested.

4. Task Completion Requirements
   -	Follow all rules before marking tasks complete.
