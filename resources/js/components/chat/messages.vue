<template>
  <div>
    <div v-if="this.type_chat == 'public' && !isSave">
       <v-text-field
      v-model="nickname"
      label="Nick Name"
      required
    ></v-text-field>
    <v-btn color="info" @click="changeStatus(true)">save</v-btn>
    </div>
    <v-list>
      <!-- progress-circular used for private-chat and room-chat
          waiting messages coming from server-->
      <v-progress-circular v-if="loading"
      :size="70"
      :width="7"
      color="purple"
      indeterminate
    ></v-progress-circular>

          <template v-for="(item) in tmpMessage">
        <!--
          if i send message show it in the right side with blue color
          if other user send message show it in left side
          re-order to fix it ... put text beside image
         use it for name and image
        -->
      <v-layout align-center :justify-end="(sender_id == item.sender.id || sender_name == item.sender.name)" :justify-start="(sender_id != item.sender.id || sender_name != item.from)" row fill-height>
             <v-list-tile
              :color="(sender_id == item.sender.id || sender_name == item.sender.name) ?'blue':'gray'"
              @click=""
            >
            <v-layout align-center row :reverse="(sender_id == item.sender.id || sender_name == item.sender.name)">
              <v-list-tile-avatar>
                <img :src="`${$store.getters.baseurl}storage/${item.sender.avatar}`">
              </v-list-tile-avatar>
              <v-list-tile-content>
                <v-list-tile-title v-html="displayName(item.sender.name)"></v-list-tile-title>
                <v-list-tile-sub-title v-html="item.content"></v-list-tile-sub-title>
              </v-list-tile-content>
            </v-layout>
            </v-list-tile>
           </v-layout>
          </template>
        </v-list>

        <v-text-field
      v-validate="'required'"
      v-model="message"
      :error-messages="errors.collect('message')"
      label="Message"
      data-vv-name="message"
      required
    ></v-text-field>
    <v-btn color="info" @click="sendMessage">send</v-btn>
  </div>
</template>
<script>
export default {
  // type_chat => room , private , public
  // recevier => who receive the message
  // sender_id => who send the message
  // sender_name => who send the message with name ... use it in public chat because  there are no auth
  // messages => messages in chat
  props: ["type_chat", "recevier", "sender_id", "sender_name", "messages"],

  created() {
    //set messages here to avoid mutation error
    this.tmpMessage = this.messages;
  },
  mounted() {
    if (this.$store.getters.isAuth) {
      if (this.type_chat != "public") {
        // get the old message from server
        // if
        // type chat not public
        // server does not save public message on database
        let payload = { id: this.$route.params.id, type_chat: this.type_chat };
        this.$store
          .dispatch("getMessage", payload)
          .then((res) => {
            this.tmpMessage = this.$store.getters.messages;
            this.loading = false;
          })
          .catch((err) => {
            this.$toaster.error(
              "Something happened error, try again or contact the support"
            );
          });
      }
    }
    this.loading = false;
  },
  data() {
    return {
      isSave: false, // to check is nick name is saved or not
      nickname: null, //save nickName here ... use in public chat
      message: null, // messages stored in vuex .. coming from server
      loading: true, // to show,hide  v-progress-circular
      tmpMessage:[{sender:{id:0,name:''}}], // template Message to void null
      toServer: "", //  to pass message by server after edit on (message)
    };
  },
  methods: {
    // check form nickname it is fill ..
    fillNickNameRequired() {
      if (this.type_chat == "public" && !this.nickname) {
        this.$toaster.error("you must fill nick name");
        return false;
      }
      return true;
    },
    // modify on isSave to confirm nickName it is same
    changeStatus(val) {
      if (this.fillNickNameRequired()) {
        this.isSave = val;
        let date = new Date();
        // use timestamp to avoid when two user enter the same name on public chat
        let timestamp = date.getTime();
         this.nickname = this.nickname + "_" + timestamp;
         this.$emit('getNickName',this.nickname);

      }
    },
    sendMessage() {
      // before send data check .. fill required
      if (!this.fillNickNameRequired()) {
        // stop here...
        return;
      }
      if (!this.message) {
        this.$toaster.error("you must fill message");
        // stop here...
        return;
      }
      //set message to send to server
      this.toServer = this.message;
      // remove messgage after copy it
      this.message = "";
// !! we can not here  use message only because , if we remove it , which will send to server empty

// if we are in public chat -> send nickname else send username
  let from =
        this.type_chat == "public"
          ? this.nickname
          : this.$store.getters.user.name;
      let data = {
        type: this.type_chat,
        to: this.recevier,
        sender: {
          id: this.$store.getters.isAuth ? this.$store.getters.user.id : -1,
          avatar: this.$store.getters.isAuth
            ? this.$store.getters.user.avatar
            : "default.jpg",
          name: from
        },
        content: this.toServer
      };
      // use default image for public users
      this.$store.dispatch("sendMessage", data).then((res) => {});
    },
     displayName(name){
      return name.split("_")[0];
    }
  },
};
</script>

