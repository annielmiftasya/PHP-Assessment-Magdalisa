function waitForCondition(conditionFn, timeout = 5000) {
    return new Promise((resolve, reject) => {
        const startTime = Date.now();
        const interval = setInterval(() => {
            if (conditionFn()) {
                clearInterval(interval);
                resolve();
            } else if (Date.now() - startTime > timeout) {
                clearInterval(interval);
                reject(new Error('Condition not met within timeout'));
            }
        }, 100);
    });
}

// Utility function to get Alpine.js data
function getAlpineData() {
    return document.querySelector('[x-data]').__x.$data;
}

function test(description, callback) {
    try {
        callback();
        console.log(`✔️ ${description}`);
    } catch (error) {
        console.error(`❌ ${description}`);
        console.error(error);
    }
}

test('Fetch current time', async () => {
    const alpineData = getAlpineData();
    if (!alpineData) throw new Error('Alpine data not found');

    await waitForCondition(() => alpineData.currentTime !== '');

    const currentTimeElement = document.querySelector('[x-text="currentTime"]');
    if (!currentTimeElement) throw new Error('Current time element not found');

    if (currentTimeElement.innerText === '') {
        throw new Error('Current time was not fetched');
    }
});

test('Add task', async () => {
    const alpineData = getAlpineData();
    if (!alpineData) throw new Error('Alpine data not found');

    const taskInput = document.querySelector('[x-model="newTask"]');
    if (!taskInput) throw new Error('Task input not found');
    
    const initialTaskCount = document.querySelectorAll('ul li').length;
    
    taskInput.value = 'Test Task';
    taskInput.dispatchEvent(new Event('input'));

    alpineData.addTask(); // Directly call the addTask method

    // Wait for Alpine.js to update
    await waitForCondition(() => document.querySelectorAll('ul li').length === initialTaskCount + 1);

    const tasks = document.querySelectorAll('ul li');
    if (tasks[tasks.length - 1].querySelector('span').innerText !== 'Test Task') {
        throw new Error('Task was not added');
    }
});

test('Edit task', async () => {
    const alpineData = getAlpineData();
    if (!alpineData) throw new Error('Alpine data not found');

    const task = document.querySelector('ul li');
    if (!task) throw new Error('Task not found');

    const editButton = task.querySelector('button.bg-yellow-500');
    if (!editButton) throw new Error('Edit button not found');

    const newText = 'Edited Task';

    // Mock prompt
    window.prompt = () => newText;
    editButton.click();

    // Wait for Alpine.js to update
    await waitForCondition(() => task.querySelector('span').innerText === newText);

    if (task.querySelector('span').innerText !== newText) {
        throw new Error('Task was not edited');
    }
});

test('Delete task', async () => {
    const alpineData = getAlpineData();
    if (!alpineData) throw new Error('Alpine data not found');

    const task = document.querySelector('ul li');
    if (!task) throw new Error('Task not found');

    const deleteButton = task.querySelector('button.bg-red-500');
    if (!deleteButton) throw new Error('Delete button not found');

    deleteButton.click();

    // Wait for the task to be removed
    await waitForCondition(() => !document.contains(task));

    if (document.contains(task)) {
        throw new Error('Task was not deleted');
    }
});

test('Complete task', async () => {
    const alpineData = getAlpineData();
    if (!alpineData) throw new Error('Alpine data not found');

    const task = document.querySelector('ul li');
    if (!task) throw new Error('Task not found');

    const completeButton = task.querySelector('button.bg-green-500');
    if (!completeButton) throw new Error('Complete button not found');

    completeButton.click();

    // Wait for Alpine.js to update
    await waitForCondition(() => task.classList.contains('line-through'));

    if (!task.classList.contains('line-through')) {
        throw new Error('Task was not marked as completed');
    }
});
