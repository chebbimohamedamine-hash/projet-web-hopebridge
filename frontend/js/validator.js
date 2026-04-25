/**
 * Validator.js - Moteur de validation professionnel sur mesure
 */
class Validator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.errors = {};
    }

    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    showError(inputName, message) {
        const input = this.form.querySelector(`[name="${inputName}"]`);
        if (!input) return;

        input.classList.add('error-border');
        
        // Trouver ou créer le span de message d'erreur
        let errorSpan = input.parentElement.querySelector('.error-msg');
        if (!errorSpan) {
            errorSpan = document.createElement('span');
            errorSpan.className = 'error-msg';
            input.parentElement.appendChild(errorSpan);
        }
        errorSpan.innerText = message;
        this.errors[inputName] = message;
    }

    clearErrors() {
        this.errors = {};
        this.form.querySelectorAll('.error-border').forEach(el => el.classList.remove('error-border'));
        this.form.querySelectorAll('.error-msg').forEach(el => el.remove());
    }

    isValid() {
        return Object.keys(this.errors).length === 0;
    }

    // Fonction utilitaire pour récupérer les données proprement
    getFormData() {
        return new FormData(this.form);
    }
}

// Export global pour être utilisé partout
window.Validator = Validator;
