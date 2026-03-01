# Security Hardening Report — Giovanni Theme

**Theme:** giovanni (custom WooCommerce theme)
**Report Date:** March 2026
**Scope:** `wp-content/themes/giovanni`

---

## Short Summary (Brief)

Security hardening of the Giovanni theme: added CSRF protection (nonce) and input sanitization for AJAX Add to Cart; validated and sanitized user input in Likes and Product Filters; fixed XSS risks by escaping output in 8 template files; enforced minimum 8-character password for registration. No breaking changes; existing functionality preserved.

**What was done (by item):**
1. AJAX Add to Cart — added nonce (CSRF protection), sanitized `variation_id` and `product_id` with `absint()`
2. Simple Likes — sanitized `post_id` with `absint()`, validated `$post_id > 0` before processing
3. Product Filters — validated taxonomy with `taxonomy_exists()`, sanitized `category_id` with `intval()`, added `isset()` check for form data
4. XSS prevention — added `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()` in 8 template files
5. Registration — enforced minimum 8-character password with validation message
6. `money/` folder — documented as unused legacy MLM code; recommended for removal

---

## Executive Summary

This report documents security improvements implemented in the Giovanni theme as part of a comprehensive security audit. The changes address CSRF risks, input validation, SQL injection vectors, XSS (Cross-Site Scripting), and authentication weaknesses.

---

## 1. AJAX Add to Cart — CSRF & Input Sanitization

### Issue
The AJAX add-to-cart handler was vulnerable to CSRF and accepted unsanitized user input for `variation_id` and `product_id`.

### Changes

**File: [`inc/__ajax.php`](inc/__ajax.php)**
- Added `check_ajax_referer('giovanni_add_to_cart', 'nonce')` at the start of `giovanni_ajax_add_to_cart()` to prevent CSRF attacks
- Sanitized `variation_id` with `absint()`: `$variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0`
- `product_id` now uses `absint()` via `apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']))`

**File: [`functions.php`](functions.php)**
- Added `add_to_cart_nonce` to `wp_localize_script()` output: `'add_to_cart_nonce' => wp_create_nonce('giovanni_add_to_cart')`

**File: [`assets/js/scripts/product-single.js`](assets/js/scripts/product-single.js)**
- Added `nonce` to AJAX request data: `nonce: window.giovanni && window.giovanni.add_to_cart_nonce ? window.giovanni.add_to_cart_nonce : ''`

---

## 2. Simple Likes — Post ID Sanitization

### Issue
`post_id` from `$_POST['post']` was used without validation, allowing potential manipulation of unrelated posts.

### Changes

**File: [`inc/__likes.php`](inc/__likes.php)**
- Sanitized `post_id` with `absint()`: `$post_id = isset($_POST['post']) ? absint($_POST['post']) : 0`
- Changed validation from `$post_id != ''` to `$post_id > 0` before processing

---

## 3. Product Filters — Taxonomy Validation & Category ID Sanitization

### Issue
- Taxonomy names were derived from URL parameters without checking if they exist
- `category_id` was passed into queries without sanitization

### Changes

**File: [`inc/__filters.php`](inc/__filters.php)**

**Taxonomy validation (lines 49–58):**
- Added `taxonomy_exists($taxonomy)` check before adding taxonomy to `tax_query`
- Prevents arbitrary taxonomy injection via URL parameters

**Category ID sanitization (line 261):**
- `$category_id` now sanitized: `$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : false`
- In `build_product_query_args()`, `category_id` is passed through `absint()` when used in `tax_query`

**Form data handling:**
- Added `isset($_POST['formData'])` check before `urldecode()` to avoid undefined index notices

---

## 4. XSS Prevention — Output Escaping

### Issue
Dynamic content from ACF and other sources was echoed without escaping, enabling Stored and Reflected XSS.

### Changes

Escaping was added across template files and theme logic:

| File | Escaping Applied |
|------|------------------|
| [`inc/__custom-functions.php`](inc/__custom-functions.php) | `esc_html()` for `$logo['name']`, `$logo['label']`; `wp_kses_post()` for `$block['content']`; `esc_attr()` for `$aria_label`, `$site_name` |
| [`template-parts/page/paragraph.php`](template-parts/page/paragraph.php) | `esc_attr()`, `esc_html()`, `esc_url()`, `wp_kses_post()` for block content and links |
| [`template-parts/page/post.php`](template-parts/page/post.php) | `esc_attr()`, `esc_html()`, `esc_url()`, `wp_kses_post()` for block fields |
| [`template-parts/info-card.php`](template-parts/info-card.php) | `esc_attr()`, `esc_html()`, `esc_url()` for `$group` data |
| [`template-parts/main-post.php`](template-parts/main-post.php) | `esc_attr()`, `esc_html()`, `esc_url()` for `$item` fields |
| [`template-parts/product-card-adv.php`](template-parts/product-card-adv.php) | `esc_url()`, `esc_attr()`, `esc_html()` for link, title, label |
| [`templates/customer-service.php`](templates/customer-service.php) | `esc_html()` for `$dashbord_name` and page title |
| [`templates/favorites.php`](templates/favorites.php) | `esc_attr()` for `$reverse`; `esc_html()` for `$group['title']`, `$item['text']`, `$group['link_label']` |

**Functions used:**
- `esc_attr()` — attribute values
- `esc_html()` — text content
- `esc_url()` — URLs
- `wp_kses_post()` — HTML content where limited tags are allowed

---

## 5. User Registration — Password Validation

### Issue
Passwords were accepted by `wp_insert_user()` without length or complexity checks.

### Changes

**File: [`inc/__registration.php`](inc/__registration.php)**
- Enforced minimum password length of 8 characters before registration
- Validation: `if (strlen($password) < 8)` with Hebrew error message: `__('הסיסמה חייבת להכיל לפחות 8 תווים.', 'giovanni')`
- Existing `check_ajax_referer()` and `sanitize_email()` / `sanitize_text_field()` usage retained

---

## 6. Existing Security Measures (Unchanged)

The following were already in place and left as-is:
- `__registration.php` — `check_ajax_referer` for registration
- `__gift-card.php` — nonce and field sanitization
- `__user-profile.php` — `current_user_can('edit_user')` and `sanitize_text_field`
- `__actions.php` — database operations via `$wpdb->prepare()`
- AJAX search — `check_ajax_referer` and `sanitize_text_field`
- Product filters — nonce in `load_more_products` and `handle_filter_products`
- WooCommerce login — `esc_attr(wp_unslash($_POST['username']))` for display

---

## 7. Note on `money/` Folder

The `money/` folder contains legacy MLM (Multi-Level Marketing) script fragments that:
- Require `V1_INSTALLED` (not defined in the project) and exit with 404 if absent
- Are not referenced by the theme or WordPress
- Use non-WordPress database and session handling
- Contain SQL injection risks if ever activated

**Recommendation:** Remove the `money/` folder if the referral/MLM functionality is not required. It is unused and increases attack surface.

---

## 8. Summary of Files Modified

| File | Changes |
|------|---------|
| `inc/__ajax.php` | Nonce check, `absint()` for variation_id and product_id |
| `inc/__likes.php` | `absint()` for post_id, validation `$post_id > 0` |
| `inc/__filters.php` | `taxonomy_exists()` validation, `intval()` for category_id |
| `inc/__registration.php` | Minimum 8-character password validation |
| `inc/__custom-functions.php` | XSS escaping for logo, blocks |
| `functions.php` | `add_to_cart_nonce` in localized script data |
| `assets/js/scripts/product-single.js` | Nonce in AJAX add-to-cart payload |
| `template-parts/page/paragraph.php` | XSS escaping |
| `template-parts/page/post.php` | XSS escaping |
| `template-parts/info-card.php` | XSS escaping |
| `template-parts/main-post.php` | XSS escaping |
| `template-parts/product-card-adv.php` | XSS escaping |
| `templates/customer-service.php` | XSS escaping |
| `templates/favorites.php` | XSS escaping |

---

*End of report*
