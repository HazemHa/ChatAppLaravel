<template>
   <v-container grid-list-md text-xs-center fluid>
    <v-layout row wrap>
     
     <v-flex xs6>
        <v-card class="scroll" height="550">
        <messages @getNickName="setNickName" :sender_name="nickName" type_chat="public" :messages="messages"></messages>
              </v-card>

      </v-flex>


       <v-flex xs6>
        <rooms></rooms>
      </v-flex>

     

    </v-layout>
   </v-container>
</template>
<script>

import messages from './chat/messages.vue'
import rooms from './chat/rooms.vue'
import Echo from "laravel-echo";

export default {

created() {
   let token = document.head.querySelector('meta[name="csrf-token"]');
  
  let EchoPublic = new Echo({
        broadcaster: "socket.io",
        host: "127.0.0.1:6001",
        csrfToken: token.content
      });
       // public channel , which not need to use to be auth 
  EchoPublic.channel('broadcast.public')
    .listen('MessageSent', (e) => {           
      this.messages.push(e.data);
      
    });

   
},
  data(){
    return {
      isSave  :false,// check the nickName is saved or not 
      nickName:'', // nickname for user
      messages:[], // messages for public chat
    }
  },
  components: {
    'messages':messages,
    'rooms':rooms, // see the rooms that are available
  },
  methods:{
    setNickName(name){
      this.nickName = name;
    }
  }
  
}
</script>
<style>
.scroll {
      overflow-y: auto;
    }
</style>

