document.addEventListener('alpine:init', () => {
    Alpine.data('todoApp', () => ({
        tasks: [],
        newTask: '',
        searchQuery: '',
        filteredTasks: [],
        currentTime: '',

        init() {
            this.filteredTasks = this.tasks;
            this.updateTimeEverySecond();
        },

        async fetchCurrentTime() {
            try {
                const response = await fetch('https://worldtimeapi.org/api/timezone/Asia/Jakarta');
                const data = await response.json();
                this.currentTime = new Date(data.datetime).toLocaleString('en-US', { timeZone: 'Asia/Jakarta' });
            } catch (error) {
                console.error('Error fetching time:', error);
            }
        },

        updateTimeEverySecond() {
            this.fetchCurrentTime();
            setInterval(() => {
                this.fetchCurrentTime();
            }, 1000); // Update every second
        },

        addTask() {
            if (this.newTask.trim() === '') return;
            this.tasks.push({ text: this.newTask, completed: false });
            this.newTask = '';
            this.filterTasks();
        },

        editTask(index) {
            const newText = prompt('Edit your task:', this.tasks[index].text);
            if (newText !== null && newText.trim() !== '') {
                this.tasks[index].text = newText;
                this.filterTasks();
            }
        },

        deleteTask(index) {
            this.tasks.splice(index, 1);
            this.filterTasks();
        },

        toggleComplete(index) {
            this.tasks[index].completed = !this.tasks[index].completed;
            this.filterTasks();
        },

        filterTasks() {
            const query = this.searchQuery.toLowerCase();
            this.filteredTasks = this.tasks.filter(task => task.text.toLowerCase().includes(query));
        }
    }));
});
