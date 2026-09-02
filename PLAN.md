# JS-Based Image Gallery Manager Plan

## Goal
Replace or supplement the PHP upload system with a fully client-side JavaScript solution using IndexedDB to store, display, remove, and override images.

---

## 1. Storage Layer — IndexedDB

Use IndexedDB (not localStorage — too small for images) to store image blobs + metadata.

- **Database name:** `mun_gallery_db`
- **Object store:** `images` (keyPath: `id`, auto-increment)
- **Each record:** `{ id, name, type, category, data (Blob), date }`
- The `category` field (`gallery` or `newsletter`) lets you filter images by section.

### Functions needed:
- `openDB()` — creates/connects to the database and object store
- Use `idb` library or native `indexedDB` API

---

## 2. Store / Upload an Image

1. User selects a file via `<input type="file">`
2. `FileReader.readAsArrayBuffer(file)` reads the file
3. Insert the record into IndexedDB with the selected category from the radio buttons
4. For newsletter override: before inserting, delete any existing record with `category === "newsletter"` first, then insert the new one

### Pseudocode:
```js
function storeImage(file, category) {
  // If newsletter, delete existing newsletter record first (override)
  // Read file as ArrayBuffer via FileReader
  // Put new record into IndexedDB
  // Re-render gallery
}
```

---

## 3. Display Images

1. Open a cursor on the `images` object store
2. For each record:
   - If `type` starts with `image/`, create an `<img>` element
   - If `type === 'application/pdf'`, create a link with a PDF badge
3. Append each card to the gallery grid container
4. Each card gets a delete button with the record's `id`

### Pseudocode:
```js
function renderGallery() {
  // Clear the grid container
  // Open cursor on 'images' store
  // For each record, create DOM elements
  // Append to grid
}
```

---

## 4. Remove an Image

1. Get the `id` of the image to delete
2. Call `transaction.objectStore('images').delete(id)`
3. Re-render the gallery

### Pseudocode:
```js
function removeImage(id) {
  // Delete from IndexedDB by id
  // Re-render gallery
}
```

---

## 5. Override / Replace an Image

The override logic lives inside `storeImage()`:

- If `category === "newsletter"`, delete any existing record with that category first
- Then insert the new file as a new record
- A checkbox `Override existing newsletter` appears only when the Newsletter radio is selected to confirm this behavior

### Pseudocode:
```js
function storeImage(file, category, override = false) {
  if (category === 'newsletter' && override) {
    deleteExistingByCategory('newsletter');
  }
  insertNewRecord(file, category);
}
```

---

## 6. UI Integration

- Replace the PHP upload form with a JS-driven form in `gallery.html`
- Radio buttons (`gallery` / `newsletter`) control the `category` field
- Checkbox appears only when `newsletter` is selected (confirm override)
- Gallery grid renders dynamically from IndexedDB on page load
- Each card has a delete button

---

## 7. Key Files to Modify

| File | Changes |
|------|---------|
| `scripts/script.js` | Add IndexedDB logic, `storeImage()`, `removeImage()`, `renderGallery()`, `overrideImage()` |
| `pages/gallery.html` | Add gallery grid container and JS-driven upload form |
| `pages/admin.php` | Keep as server-side backup or remove entirely |
| `pages/newsletter.html` | Add content (team info, schedule, etc.) |

---

## 8. Alternative Simpler Approach

If IndexedDB feels heavy, use **`localStorage` for metadata + Base64 strings** for small images only. Not recommended for large images or PDFs due to the 5MB localStorage limit.

---

## Implementation Order

1. Set up IndexedDB (`openDB()`)
2. Build `storeImage()` with override logic
3. Build `renderGallery()` to display stored images
4. Build `removeImage()` for delete functionality
5. Wire up the form, radio buttons, and checkbox
6. Style the gallery grid and cards
7. Test all flows (upload, view, delete, override)