<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-10">
        <h1>Today's Updated News</h1>
      </div>
      <div class="col-2">
        <button @click="loadNews()">Load Recent</button>
      </div>
    </div>
    <div class="row">
      <div class="col-4" v-for="(article, i) in articles" :key="i">
        <div class="card">
          <img
            class="card-img-top"
            :src="article.urlToImage"
            :alt="article.title"
          />
          <div class="card-body">
            <h3 class="card-title">{{ article.title }}</h3>
            <p class="card-text">{{ article.description }}</p>
            <a :href="article.url" class="btn btn-primary">Click to View</a>
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
      url:
        "http://newsapi.org/v2/everything?domains=wsj.com&apiKey=f6282a3dc1f74d18a45f5eec91513e71",
      articles: [],
    };
  },
  mounted() {
    this.loadNews();
  },
  methods: {
    async loadNews() {
      await fetch(this.url)
        .then((res) => res.json())
        .then((data) =>
          data.status !== "error"
            ? (this.articles = data.articles)
            : console.log(data.message)
        );
    },
  },
};
</script>
