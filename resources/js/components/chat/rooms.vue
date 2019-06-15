<template>
     <v-list subheader>
       <!-- display the info about rooms -->
          <v-subheader>Rooms</v-subheader>
          <v-list-tile
            v-for="(item,index) in rooms"
            :key="item.name"
            avatar
            @click="LinkTo('room',{id:item.id})"
          >
            <v-list-tile-avatar>
              <img :src="`${$store.getters.baseurl}storage/default_room.png`">
            </v-list-tile-avatar>

            <v-list-tile-content>
              <v-list-tile-title v-html="item.name"></v-list-tile-title>
            </v-list-tile-content>

            <v-list-tile-action>
              <v-icon :color="item.active ? 'teal' : 'grey'">chat_bubble</v-icon>

            </v-list-tile-action>
          </v-list-tile>
        </v-list>
</template>
<script>
  export default {
    created() {
      this.$store.dispatch('getRooms')
    .then((res)=>{
      this.rooms = res.data;
    })
    .catch((err)=>{
    });
    },
    data () {
      return {
        rooms: [
          { active: true, title: 'Jason Oner', avatar: 'default_room.png' },
        ],
      }
    },
    methods:{
      // go to room when click on it .. if the user auth
       LinkTo(route,params){
         if(this.$store.getters.isAuth){
          this.$router.push({name:route,params: params});
         }else{
            this.$toaster.info(
            "you must register to enter in rooms"
          );
         }
      },
    }
  }
</script>
