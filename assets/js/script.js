/**
 * INVENTORY MANAGEMENT SYSTEM - JAVASCRIPT
 * Form Validation & Dynamic Interactions
 */

// =============================================
// FORM VALIDATION
// =============================================

/**
 * Validate Email Format
 * @param {string} email - Email to validate
 * @return {boolean} - True if valid, false otherwise
 */
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validate Phone Number
 * @param {string} phone - Phone number to validate
 * @return {boolean} - True if valid, false otherwise
 */
function validatePhone(phone) {
    const phoneRegex = /^[0-9\-\+\(\)\s]*$/;
    return phoneRegex.test(phone) && phone.length >= 10;
}

/**
 * Validate Form Fields
 * @param {object} formData - Object containing form fields
 * @return {object} - Validation result with errors array
 */
function validateForm(formData) {
    const errors = [];

    // Check required fields
    for (let field in formData) {
        if (formData[field].trim() === '') {
            errors.push(field + ' is required');
        }
    }

    return {
        isValid: errors.length === 0,
        errors: errors
    };
}

/**
 * Validate Product Form
 * @return {boolean} - True if form is valid
 */
function validateProductForm() {
    const productName = document.getElementById('product_name')?.value || '';
    const productCode = document.getElementById('product_code')?.value || '';
    const categoryId = document.getElementById('category_id')?.value || '';
    const supplierId = document.getElementById('supplier_id')?.value || '';
    const unitPrice = document.getElementById('unit_price')?.value || '';

    const errors = [];

    if (productName.trim() === '') {
        errors.push('Product Name is required');
    }

    if (productCode.trim() === '') {
        errors.push('Product Code is required');
    }

    if (categoryId === '' || categoryId === '0') {
        errors.push('Category is required');
    }

    if (supplierId === '' || supplierId === '0') {
        errors.push('Supplier is required');
    }

    if (unitPrice === '' || isNaN(unitPrice) || parseFloat(unitPrice) < 0) {
        errors.push('Unit Price must be a valid positive number');
    }

    if (errors.length > 0) {
        displayErrors(errors);
        return false;
    }

    return true;
}

/**
 * Validate Supplier Form
 * @return {boolean} - True if form is valid
 */
function validateSupplierForm() {
    const supplierName = document.getElementById('supplier_name')?.value || '';
    const email = document.getElementById('email')?.value || '';
    const phone = document.getElementById('phone')?.value || '';

    const errors = [];

    if (supplierName.trim() === '') {
        errors.push('Supplier Name is required');
    }

    if (email !== '' && !validateEmail(email)) {
        errors.push('Email format is invalid');
    }

    if (phone !== '' && !validatePhone(phone)) {
        errors.push('Phone number is invalid');
    }

    if (errors.length > 0) {
        displayErrors(errors);
        return false;
    }

    return true;
}

/**
 * Validate Inventory Form
 * @return {boolean} - True if form is valid
 */
function validateInventoryForm() {
    const currentStock = document.getElementById('current_stock')?.value || '';
    const minimumStock = document.getElementById('minimum_stock')?.value || '';
    const maximumStock = document.getElementById('maximum_stock')?.value || '';

    const errors = [];

    if (currentStock === '' || isNaN(currentStock) || parseInt(currentStock) < 0) {
        errors.push('Current Stock must be a valid positive number');
    }

    if (minimumStock === '' || isNaN(minimumStock) || parseInt(minimumStock) < 0) {
        errors.push('Minimum Stock must be a valid positive number');
    }

    if (maximumStock === '' || isNaN(maximumStock) || parseInt(maximumStock) < 0) {
        errors.push('Maximum Stock must be a valid positive number');
    }

    if (parseInt(minimumStock) > parseInt(maximumStock)) {
        errors.push('Minimum Stock cannot be greater than Maximum Stock');
    }

    if (errors.length > 0) {
        displayErrors(errors);
        return false;
    }

    return true;
}

// =============================================
// ERROR & SUCCESS MESSAGE DISPLAY
// =============================================

/**
 * Display Error Messages
 * @param {array} errors - Array of error messages
 */
function displayErrors(errors) {
    const alertContainer = document.getElementById('alert-container');
    
    if (!alertContainer) {
        alert(errors.join('\n'));
        return;
    }

    let alertHTML = '<div class="alert alert-danger">';
    alertHTML += '<strong>Validation Errors:</strong><ul>';
    
    errors.forEach(error => {
        alertHTML += '<li>' + error + '</li>';
    });
    
    alertHTML += '</ul></div>';
    
    alertContainer.innerHTML = alertHTML;
    
    // Scroll to alert
    alertContainer.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Display Success Message
 * @param {string} message - Success message
 */
function displaySuccess(message) {
    const alertContainer = document.getElementById('alert-container');
    
    if (!alertContainer) {
        alert(message);
        return;
    }

    const alertHTML = '<div class="alert alert-success">' + message + '</div>';
    alertContainer.innerHTML = alertHTML;
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 3000);
}

// =============================================
// CONFIRMATION DIALOGS
// =============================================

/**
 * Confirm Delete Action
 * @param {string} itemName - Name of item to delete
 * @return {boolean} - True if confirmed, false otherwise
 */
function confirmDelete(itemName) {
    return confirm('Are you sure you want to delete "' + itemName + '"?\nThis action cannot be undone.');
}

/**
 * Confirm Bulk Delete
 * @return {boolean} - True if confirmed
 */
function confirmBulkDelete() {
    return confirm('Are you sure you want to delete the selected items?\nThis action cannot be undone.');
}

// =============================================
// TABLE OPERATIONS
// =============================================

/**
 * Select/Deselect All Checkboxes
 * @param {element} checkbox - The "Select All" checkbox
 */
function selectAllCheckboxes(checkbox) {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_items"]');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

/**
 * Get Selected Items from Table
 * @return {array} - Array of selected item IDs
 */
function getSelectedItems() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_items"]:checked');
    const selectedIds = [];
    
    checkboxes.forEach(checkbox => {
        selectedIds.push(checkbox.value);
    });
    
    return selectedIds;
}

/**
 * Check if Any Item is Selected
 * @return {boolean} - True if at least one item is selected
 */
function hasSelectedItems() {
    return getSelectedItems().length > 0;
}

// =============================================
// SEARCH & FILTER
// =============================================

/**
 * Filter Table Rows by Search Term
 * @param {string} searchTerm - Search keyword
 * @param {string} tableId - ID of the table to filter
 */
function filterTable(searchTerm, tableId) {
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    const lowerSearchTerm = searchTerm.toLowerCase();
    
    Array.from(rows).forEach(row => {
        const text = row.textContent.toLowerCase();
        
        if (text.includes(lowerSearchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Add Real-time Search to Table
 * @param {string} inputId - ID of search input
 * @param {string} tableId - ID of table to search
 */
function enableTableSearch(inputId, tableId) {
    const searchInput = document.getElementById(inputId);
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterTable(this.value, tableId);
        });
    }
}

// =============================================
// FORM UTILITIES
// =============================================

/**
 * Reset Form to Default Values
 * @param {string} formId - ID of the form
 */
function resetFormValues(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
    }
}

/**
 * Clear Form Errors
 */
function clearFormErrors() {
    const alertContainer = document.getElementById('alert-container');
    if (alertContainer) {
        alertContainer.innerHTML = '';
    }
}

/**
 * Disable Form Submission Button During Submit
 * @param {string} formId - ID of the form
 */
function disableSubmitButton(formId) {
    const form = document.getElementById(formId);
    if (form) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
        }
    }
}

/**
 * Enable Form Submission Button
 * @param {string} formId - ID of the form
 */
function enableSubmitButton(formId) {
    const form = document.getElementById(formId);
    if (form) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Submit';
        }
    }
}

// =============================================
// NUMBER FORMATTING
// =============================================

/**
 * Format Number as Currency
 * @param {number} value - Number to format
 * @param {string} currency - Currency symbol (default: $)
 * @return {string} - Formatted currency string
 */
function formatCurrency(value, currency = '$') {
    return currency + parseFloat(value).toFixed(2);
}

/**
 * Format Number with Thousand Separator
 * @param {number} value - Number to format
 * @return {string} - Formatted number string
 */
function formatNumber(value) {
    return parseInt(value).toLocaleString();
}

// =============================================
// DATE UTILITIES
// =============================================

/**
 * Format Date to DD/MM/YYYY
 * @param {string|date} dateString - Date to format
 * @return {string} - Formatted date
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    
    return day + '/' + month + '/' + year;
}

/**
 * Get Current Date in YYYY-MM-DD Format
 * @return {string} - Today's date
 */
function getTodayDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    
    return year + '-' + month + '-' + day;
}

// =============================================
// DOM READY - Initialize Scripts
// =============================================

/**
 * Wait for DOM to be fully loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips or other global features here
    console.log('Inventory Management System Initialized');
});

// =============================================
// EXPORT FOR USE IN FORMS
// =============================================

// Make functions available globally
window.validateForm = validateForm;
window.validateProductForm = validateProductForm;
window.validateSupplierForm = validateSupplierForm;
window.validateInventoryForm = validateInventoryForm;
window.confirmDelete = confirmDelete;
window.selectAllCheckboxes = selectAllCheckboxes;
window.filterTable = filterTable;
window.enableTableSearch = enableTableSearch;
window.formatCurrency = formatCurrency;
window.formatNumber = formatNumber;
window.formatDate = formatDate;
