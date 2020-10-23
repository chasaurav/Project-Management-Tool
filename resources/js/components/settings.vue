<template>
  <div class="container">
    <form @submit.prevent="updateSettings()">
      <div class="row">
        <div class="col-4">
          <div class="form-group">
            <label>Project Start Date</label>
            <input
              type="date"
              class="form-control"
              v-model="startDate"
              required
            />
          </div>
        </div>
        <div class="col-4">
          <div class="form-group">
            <label>Working Hours (per Day)</label>
            <input
              type="number"
              class="form-control text-right"
              placeholder="in Hours"
              min="1"
              v-model="workingHour"
              required
            />
          </div>
        </div>
        <div class="col-4">
          <div class="form-group">
            <label>Work Start Time (per Day)</label>
            <input
              type="time"
              class="form-control text-right"
              v-model="startTime"
              required
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-8">
          <div v-if="settings" class="text-danger">
            <strong>Start Date: </strong>
            <span>{{ settings.startDate }}</span>
            <strong>Working Hours: </strong>
            <span>{{ settings.workHours }} hrs.</span>
            <strong>Work Start Time: </strong>
            <span>{{ settings.workTime }}</span>
          </div>
        </div>
        <div class="col-4">
          <div class="form-group">
            <input
              type="submit"
              class="btn btn-primary btn-block"
              value="Update"
            />
          </div>
        </div>
      </div>
    </form>
  </div>
</template>


<script>
export default {
  data() {
    return {
      settings: null,
      startDate: null,
      workingHour: null,
      startTime: null,
    };
  },
  mounted() {
    this.getSettings();
  },
  methods: {
    getSettings() {
      fetch("/settings")
        .then((res) => res.json())
        .then((data) => {
          this.settings = data[0];
        });
    },
    updateSettings() {
      const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
      fetch("/settings", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          _token: CSRF_TOKEN,
          startDate: this.startDate,
          workingHour: this.workingHour,
          startTime: this.startTime,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          this.startDate = null;
          this.workingHour = null;
          this.startTime = null;
          this.$store.dispatch("getDetailsForSchedule");
          this.getSettings();
        });
    },
  },
};
</script>
