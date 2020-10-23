<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="row">
          <h1>Task Component</h1>
        </div>
        <form @submit.prevent="saveTask()">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Task Name</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Enter Name Here"
                  v-model="taskName"
                  required
                />
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <label>Time Required</label>
                <input
                  type="number"
                  step="any"
                  min="1"
                  class="form-control text-right"
                  placeholder="in Hours"
                  v-model="taskTime"
                />
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>{{ msg }}</label>
                <input
                  v-if="!editing"
                  type="submit"
                  class="btn btn-primary btn-block"
                  value="Save Task"
                />
                <div class="row" v-else>
                  <div class="col-6">
                    <input
                      type="button"
                      class="btn btn-primary btn-block"
                      value="Update Task"
                      @click="updateTask()"
                    />
                  </div>
                  <div class="col-6">
                    <input
                      type="submit"
                      class="btn btn-danger btn-block"
                      value="Cancel"
                      @click="cancelEdit()"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-12">
            <h3>Task List</h3>
            <table class="table table-borderless table-condensed">
              <thead>
                <tr>
                  <th>Name</th>
                  <th width="15%" class="text-center">Time Required</th>
                  <th width="15%" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody v-for="(task, i) in tasks" :key="i">
                <tr>
                  <td>{{ task.name }}</td>
                  <td class="text-center">{{ task.time }} hrs</td>
                  <td class="text-center">
                    <button
                      type="button"
                      class="btn btn-link btn-sm"
                      @click="editTask(task.id)"
                    >
                      Edit
                    </button>
                    <button
                      type="button"
                      class="btn btn-link btn-sm"
                      @click="deleteTask(task.id)"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tasks: [],
      id: null,
      taskName: "",
      taskTime: 0,
      editing: false,
      msg: null,
    };
  },
  mounted() {
    this.loadTask();
  },
  methods: {
    loadTask() {
      fetch("/task")
        .then((res) => res.json())
        .then((data) => (this.tasks = data));
    },
    editTask(id) {
      for (let i = 0; i < this.tasks.length; i++) {
        if (this.tasks[i].id == id) {
          this.id = this.tasks[i].id;
          this.taskName = this.tasks[i].name;
          this.taskTime = this.tasks[i].time;
          this.editing = true;
        }
      }
    },
    cancelEdit() {
      this.editing = false;
      this.id = null;
      this.taskName = null;
      this.taskTime = null;
    },
    updateTask() {
      const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
      if (confirm("Are you sure ?")) {
        for (let i = 0; i < this.tasks.length; i++) {
          if (this.tasks[i].id == this.id) {
            fetch(`/task/update/${this.id}`, {
              method: "PUT",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                _token: CSRF_TOKEN,
                id: this.id,
                taskName: this.taskName,
                taskTime: this.taskTime,
              }),
            })
              .then((res) => res.json())
              .then((data) => {
                this.msg = data;
                this.tasks[i].id = this.id;
                this.tasks[i].name = this.taskName;
                this.tasks[i].time = this.taskTime;
                this.cancelEdit();
                this.$store.dispatch("getDetailsForSchedule");
              });
          }
        }
      }
    },
    deleteTask(id) {
      const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
      if (confirm("Are you sure ?")) {
        fetch(`/task/delete/${id}`, {
          method: "delete",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ _token: CSRF_TOKEN, id: id }),
        })
          .then((res) => res.json())
          .then((data) => {
            this.tasks = this.tasks.filter((task) => task.id !== id);
            this.$store.dispatch("getDetailsForSchedule");
          });
      }
    },
    saveTask() {
      const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
      fetch("/task/create", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          _token: CSRF_TOKEN,
          taskName: this.taskName,
          taskTime: this.taskTime,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          this.msg = data;
          this.tasks.push({
            id: this.id,
            name: this.taskName,
            time: this.taskTime,
          });
          this.cancelEdit();
          this.$store.dispatch("getDetailsForSchedule");
        });
    },
  },
};
</script>
