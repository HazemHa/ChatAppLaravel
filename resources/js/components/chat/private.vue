<template>
   <v-container grid-list-md text-xs-center fluid>



    <v-layout row wrap>
     <v-btn class="info" @click="back"> < back </v-btn>
     <v-flex xs12>
        <v-card class="scroll" height="550">
           <!--  to distinguish your messages from others -->
        <messages :sender_id="this.$store.getters.user.id" type_chat="private" :recevier="this.$route.params.id"></messages>
              </v-card>

      </v-flex>


     
     

    </v-layout>
   </v-container>
</template>
<script>
import currentUser from "./currentUser.vue";
import messagesComponent from "./messages.vue";

export default {
  mounted() {
    //  sum id sender with id recevier to broadcast in the same channel
    let nameChannel = `message.private.${parseInt(this.$route.params.id) +
      parseInt(this.$store.getters.user.id)}`;
    this.$store.dispatch("setPrivateChannel", nameChannel);
    Echo.private(this.$store.getters.privateChannel) //privateChannel
      .listen("MessageSent", (e) => {
        //
        this.$store.getters.messages.push(e.data);
      });
  },
  components: {
    currentUser: currentUser,
    messages: messagesComponent
  },
  data() {
    return {
      messages: []
    };
  },
  updated() {
    var container = this.$el.querySelector(".scroll");
    container.scrollTop = container.scrollHeight;
  },
  methods: {
    back() {
      Echo.leave(this.$store.getters.privateChannel);
      this.$router.go(-1);
    }
  }
};
</script>
<style>
.scroll {
  overflow-y: auto;
}
</style>

