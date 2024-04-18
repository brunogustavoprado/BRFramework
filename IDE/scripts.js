// Inicializa o CodeMirror para cada editor
const htmlEditor = CodeMirror.fromTextArea(document.getElementById('htmlCode'), {
    mode: 'xml',
    theme: 'dracula',
    lineNumbers: true,
    autoCloseTags: true,
    matchTags: {bothTags: true},
});
const cssEditor = CodeMirror.fromTextArea(document.getElementById('cssCode'), {
    mode: 'css',
    theme: 'dracula',
    lineNumbers: true,
});
const jsEditor = CodeMirror.fromTextArea(document.getElementById('jsCode'), {
    mode: 'javascript',
    theme: 'dracula',
    lineNumbers: true,
});

// Função para abrir arquivos HTML/CSS/JS
document.getElementById('openFileButton').addEventListener('click', function() {
    document.getElementById('fileInput').click();
});

document.getElementById('fileInput').addEventListener('change', function() {
    const files = this.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        reader.onload = function(event) {
            const contents = event.target.result;
            const fileType = getFileType(file.name);
            switch (fileType) {
                case 'html':
                    htmlEditor.setValue(contents);
                    break;
                case 'css':
                    cssEditor.setValue(contents);
                    break;
                case 'js':
                    jsEditor.setValue(contents);
                    break;
            }
            updatePreview();
        };
        reader.readAsText(file);
    }
});

// Função para salvar o arquivo atual
document.getElementById('saveFileButton').addEventListener('click', function() {
    const activeTab = document.querySelector('.tab.active');
    let content = '';
    switch (activeTab.dataset.type) {
        case 'html':
            content = htmlEditor.getValue();
            break;
        case 'css':
            content = cssEditor.getValue();
            break;
        case 'js':
            content = jsEditor.getValue();
            break;
    }
    const blob = new Blob([content], {type: 'text/plain'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'file.' + activeTab.textContent.toLowerCase();
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
});

// Atualiza a visualização prévia quando o conteúdo do editor muda
function updatePreview() {
    const html = htmlEditor.getValue();
    const css = "<style>" + cssEditor.getValue() + "</style>";
    const js = "<script>" + jsEditor.getValue() + "</script>";
    const preview = document.getElementById('preview').contentWindow.document;

    preview.open();
    preview.write(html + css + js);
    preview.close();
}

// Atualiza o tema quando o usuário seleciona uma opção
document.getElementById('theme').addEventListener('change', function() {
    const theme = this.value;
    htmlEditor.setOption('theme', theme);
    cssEditor.setOption('theme', theme);
    jsEditor.setOption('theme', theme);
});

// Atualiza o editor e o preview quando uma aba é clicada
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelector('.tab.active').classList.remove('active');
        this.classList.add('active');
        updatePreview();
    });
});

// Função auxiliar para obter o tipo de arquivo com base na extensão
function getFileType(fileName) {
    const extension = fileName.split('.').pop().toLowerCase();
    if (extension === 'html') {
        return 'html';
    } else if (extension === 'css') {
        return 'css';
    } else if (extension === 'js') {
        return 'js';
    } else {
        return 'unknown';
    }
}
