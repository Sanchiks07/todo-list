// pulkstenis
function startTime() {
    const now = new Date();
    const time = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit', 
        hour12: true 
    });

    document.getElementById('txt').innerHTML =  time;

    setTimeout(startTime, 500);
}
window.onload = () => {
    startTime();

    // applies dark mode pēc lapas reload
    if (localStorage.getItem("darkMode") === "true") {
        document.body.classList.add("dark-mode");
        document.getElementById("dark-mode").textContent = "Light";
    }
};

// dark mode 
function darkMode() {
    const element = document.body;
    const button = document.getElementById("dark-mode");
    const isNowInDarkMode = element.classList.toggle("dark-mode");

    // saglabā dark mode pēc lapas reload
    localStorage.setItem("darkMode", isNowInDarkMode);

    if (isNowInDarkMode) {
        button.textContent = "Light";
    } else {
        button.textContent = "Dark";
    }
}

// parāda task description
function showDesc(taskDesc) {
    const el = taskDesc.querySelector('.task-description');
    if (el.style.display === 'none' || el.style.display === '') {
        el.style.display = 'block';
    } else {
        el.style.display = 'none';
    }
}

// navbar pogas
function showTasks(showClass, hideClass1, hideClass2, hideClass3, hideClass4) {
    document.querySelector('.' + showClass).style.display='block';
    document.querySelector('.' + hideClass1).style.display='none';
    document.querySelector('.' + hideClass2).style.display='none';
    document.querySelector('.' + hideClass3).style.display='none';
    document.querySelector('.' + hideClass4).style.display='none';
}

// parāda pareizo formu (add vai edit) un ievieto noteiktā task value priekš edit
function editTask(button) {
    document.querySelector('.edit-task').style.display='block';
    document.querySelector('.new-task').style.display='none';

    const taskDiv = button.closest('.tasks-output');

    const id = taskDiv.dataset.id;
    const list = taskDiv.dataset.list;
    const title = taskDiv.querySelector('.task-title').textContent.trim();
    const description = taskDiv.querySelectorAll('.task-description p')[1].textContent.trim();
    const due_date = taskDiv.querySelector('.task-date').dataset.rawDate;

    const form = document.querySelector('.edit-task form');
    form.querySelector('input[name="id"]').value = id;
    form.querySelector('input[name="title"]').value = title;
    form.querySelector('textarea[name="description"]').value = description;
    form.querySelector('input[name="due_date"]').value = due_date;
    form.querySelector('select[name="list"]').value = list;
}

// cancel edit
function cancelEdit() {
    document.querySelector('.new-task').style.display='block';
    document.querySelector('.edit-task').style.display='none';
}

// prieks navbar saisinasans ar toogle
const container = document.querySelector('.container');
const menuToggle = document.getElementById('menu-toggle');

menuToggle.addEventListener('click', () => {
    container.classList.toggle('nav-collapsed');
});