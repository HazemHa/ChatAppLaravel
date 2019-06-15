
<template>
<div>
   

    <v-list subheader>
          <v-subheader>Current User</v-subheader>
          <v-list-tile
            v-for="item in users"
            :key="item.name"
            avatar
            @click=""
          >
            <v-list-tile-avatar>
             <img :src="`${$store.getters.baseurl}storage/${item.avatar}`"> 
            </v-list-tile-avatar>

            <v-list-tile-content>
              <v-list-tile-title v-html="item.name"></v-list-tile-title>
            </v-list-tile-content>

            <v-list-tile-action>
      <v-layout  justify-end row fill-height>

               <v-btn fab dark small @click="createNewChat(item.id)">
              <v-icon dark>chat_bubble</v-icon>
                </v-btn>

             <v-btn fab dark small @click="addToFirend(item.id)">
               <v-icon dark>add</v-icon>
               </v-btn>
      </v-layout>
             
            </v-list-tile-action>
          </v-list-tile>
        </v-list>







        <v-dialog
      v-model="dialog"
      max-width="250"
    >
      <v-card>
        <v-card-title class="headline">?? (-_-) ??</v-card-title>
        <v-card-text>
          {{msgDialog}}
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            flat="flat"
            @click="dialog = false"
          >
            Agree
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

        </div>

</template>

<script>
export default {
  // current users they are in room
  props: ["users"],
  data(){
   return {
     dialog: false,//this is used when you want add user as a friend , show dialog
     msgDialog:'',  // message dialog to show user the result of handler process
   }

  },
  methods: {
    // go to private chat with user you click on him
    createNewChat(rece_id) {

      if(this.sameUser(rece_id,'c')){
        this.$router.push({ name: "private", params: { id: rece_id } });

      }
    },
     // add Firend with user you click on him
    addToFirend(fri_id){
      // flags c ,f it is to show the correct message
       if(this.sameUser(fri_id,'f')){
     // alert('add to friend :'+fri_id);
      let data = {friend_id:fri_id}
      this.$store.dispatch('addFriend',data)
      .then((res)=>{
        
         if (res.data.success) {
              this.$toaster.success(res.data.success);
            } else if (res.data.error) {
              this.$toaster.error(res.data.error);
            }

      }).catch((err)=>{
           this.$toaster.error('Something happened error, try again or contact the support');
      })
       }
    },
    //show current message for user,
    sameUser(user_id,type){
    
if(user_id == this.$store.getters.user.id){
  if(type == 'f'){ // when user click on his name
      this.msgDialog = "you can not add yourself ";

  }else if('c'){
      this.msgDialog = "you can chat with  yourself , you can do it  without make server busy";

  }
         this.dialog = true;
         return false;
      }
      return true;
    }
  }
};
</script>