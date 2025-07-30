<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Replace Button with Selected Texts</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    .container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
    justify-content: center;
}

.select-btn, .option, .done-btn {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border: 2px solid #007bff;
    background-color: white;
    color: #007bff;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
    margin: 5px;
}

.select-btn:hover, .option:hover, .done-btn:hover {
    background-color: #007bff;
    color: white;
}

.options {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.option.selected {
    background-color: #007bff;
    color: white;
}

    </style>
</head>
<body>
    <div class="container">
        <button id="selectButton" class="select-btn">Click to Select Texts</button>
        <div id="options" class="options" style="display: none;">
            <button class="option" data-text="Text Option 1">Text Option 1</button>
            <button class="option" data-text="Text Option 2">Text Option 2</button>
            <button class="option" data-text="Text Option 3">Text Option 3</button>
            <button class="option" data-text="Text Option 4">Text Option 4</button>
            <button class="option" data-text="Text Option 5">Text Option 5</button>
        </div>
        <button id="doneButton" class="done-btn" style="display: none;">Done</button>
    </div>

    <script src="script.js"></script>
    <script>
        const selectButton = document.getElementById('selectButton');
const options = document.getElementById('options');
const doneButton = document.getElementById('doneButton');
const optionButtons = document.querySelectorAll('.option');

selectButton.addEventListener('click', () => {
    options.style.display = 'flex';
    doneButton.style.display = 'inline-block';
});

optionButtons.forEach(button => {
    button.addEventListener('click', () => {
        button.classList.toggle('selected');
    });
});

doneButton.addEventListener('click', () => {
    const selectedTexts = [];
    optionButtons.forEach(button => {
        if (button.classList.contains('selected')) {
            selectedTexts.push(button.getAttribute('data-text'));
        }
    });

    if (selectedTexts.length > 0) {
        selectButton.textContent = selectedTexts.join(', ');
    } else {
        selectButton.textContent = 'Click to Select Texts';
    }

    options.style.display = 'none';
    doneButton.style.display = 'none';
});

    </script>
</body>
</html>
