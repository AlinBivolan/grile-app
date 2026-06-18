function normalizeRomanianText(value) {
    return value
        // Diacritice copiate greșit din PDF
        .replace(/,s/g, 'ș')
        .replace(/,S/g, 'Ș')
        .replace(/,t/g, 'ț')
        .replace(/,T/g, 'Ț')

        // Variantele vechi cu sedilă
        .replace(/ş/g, 'ș')
        .replace(/Ş/g, 'Ș')
        .replace(/ţ/g, 'ț')
        .replace(/Ţ/g, 'Ț')

        // Probleme frecvente din PDF-uri
        .replace(/\bın\b/g, 'în')
        .replace(/\bıntr/g, 'într')
        .replace(/\bıntre/g, 'între')
        .replace(/\bıntr-un\b/g, 'într-un')
        .replace(/\bıntr-o\b/g, 'într-o');
}

function activateRomanianTextFix() {
    document.querySelectorAll('.auto-diacritics').forEach((field) => {
        if (field.dataset.romanianFixReady === '1') {
            return;
        }

        field.dataset.romanianFixReady = '1';

        field.addEventListener('input', () => {
            const start = field.selectionStart;
            const end = field.selectionEnd;
            const normalized = normalizeRomanianText(field.value);

            if (normalized !== field.value) {
                field.value = normalized;

                if (
                    typeof field.setSelectionRange === 'function' &&
                    start !== null &&
                    end !== null
                ) {
                    field.setSelectionRange(start, end);
                }
            }
        });

        field.addEventListener('paste', () => {
            window.setTimeout(() => {
                field.value = normalizeRomanianText(field.value);
                field.dispatchEvent(new Event('input', { bubbles: true }));
            }, 0);
        });
    });
}

document.addEventListener('DOMContentLoaded', activateRomanianTextFix);
document.addEventListener('alpine:initialized', activateRomanianTextFix);