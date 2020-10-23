<template>
  <div class="container">
    <div class="row">
      {{ this.$store.state.counter }}
      <table class="table table-borderless">
        <thead>
          <tr>
            <th>Task Name</th>
            <th>Task Start At</th>
            <th>Task Time</th>
            <th>Task End At</th>
          </tr>
        </thead>
        <tbody v-for="(datum, i) in data" :key="i">
          <tr>
            <td>{{ datum.name }}</td>
            <td>{{ datum.startAt }}</td>
            <td>{{ datum.time }} hrs</td>
            <td>{{ datum.endAt }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      data: [],
    };
  },
  computed: {
    ...mapGetters(["getTasks"]),
  },
  watch: {
    getTasks(data) {
      this.renderTask(data.tasks, data.settings);
    },
  },
  mounted() {
    this.loadDetails();
  },
  methods: {
    loadDetails() {
      fetch("/schedule")
        .then((res) => res.json())
        .then((data) => this.renderTask(data.tasks, data.settings));
    },
    renderTask(tasks, settings) {
      this.data = [];
      // Project starting date
      let projectStartDate = settings.startDate;
      // Default work start date/time each day
      let workStartDateTime = new Date(
        projectStartDate + ", " + settings.workTime
      );
      // Hours to work each day
      let workPerDay = settings.workHours;
      // Time untill work off each day
      let workEndTime = new Date(workStartDateTime);
      workEndTime.setHours(workEndTime.getHours() + parseInt(workPerDay));

      // Rest Time for reseting the "taskEndTime" variable in calculateDates fn()
      let resetTime = {
        hour: workStartDateTime.getHours(),
        mins: workStartDateTime.getMinutes(),
        sec: workStartDateTime.getSeconds(),
      };

      tasks.map(({ name, time }) => {
        // Check if day is sat/sun or task complete/pending
        let check = this.isWeekendorWorkOff(
          workStartDateTime,
          time,
          workEndTime,
          resetTime
        );
        // Pushing data to render in table
        this.data.push({
          name: name,
          startAt: workStartDateTime.toLocaleString(),
          time: time,
          endAt: check.taskEndDate.toLocaleString(),
        });
        // Setting Work Start & Work End Date/Time for next iteration
        workStartDateTime = check.taskEndDate;
        workEndTime = new Date(
          workStartDateTime.getFullYear(),
          workStartDateTime.getMonth(),
          workStartDateTime.getDate(),
          workEndTime.getHours(),
          workEndTime.getMinutes(),
          workEndTime.getSeconds()
        );
      });
    },
    isWeekendorWorkOff(projectStartDateTime, taskTime, workEndTime, resetTime) {
      let status, taskEndTime, newTaskEndTime;

      taskEndTime = new Date(projectStartDateTime);
      taskEndTime.setHours(taskEndTime.getHours() + parseInt(taskTime));

      // Its Saturday / Sunday
      if ([0, 6].includes(taskEndTime.getDay())) {
        taskEndTime = this.calculateDates(taskEndTime, workEndTime, resetTime);
      } else {
        // Working hour completed for the day
        if (taskEndTime > workEndTime) {
          taskEndTime = this.calculateDates(
            taskEndTime,
            workEndTime,
            resetTime
          );
        } else {
          // Task Completed
          console.log("Task is already complete.");
        }
      }

      return {
        status: status,
        taskEndDate: taskEndTime,
      };
    },
    calculateDates(taskEndTime, workEndTime, resetTime) {
      // Find the time difference between work end and task end
      let diff = (taskEndTime - workEndTime) / (60 * 60 * 1000);

      // Setting to default work start time for next day
      taskEndTime = new Date(
        taskEndTime.getFullYear(),
        taskEndTime.getMonth(),
        taskEndTime.getDate(),
        resetTime.hour,
        resetTime.mins,
        resetTime.sec
      );

      // Now add pending work hours to the default work start time
      taskEndTime.setHours(taskEndTime.getHours() + diff);

      // Increase by 1 day and agian check for Sat/Sun
      taskEndTime.setDate(taskEndTime.getDate() + 1);
      if ([0, 6].includes(taskEndTime.getDay())) {
        do {
          taskEndTime.setDate(taskEndTime.getDate() + 1);
        } while ([0, 6].includes(taskEndTime.getDay()));
      }

      return taskEndTime;
    },
  },
};
</script>
