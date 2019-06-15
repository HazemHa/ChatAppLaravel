<template>
    <v-container grid-list-xs>

         <v-layout fill-height>
      <v-flex xs12 sm10 md8 lg6>
      <v-card>
        <v-form ref="form" v-model="valid" lazy-validation>
        <v-card-text>
        <v-text-field
      v-model="name"
      :rules="nameRules"
      :counter="30"
      label="Name"
      required
    ></v-text-field>
    <v-text-field
      v-model="email"
      :rules="emailRules"
      label="E-mail"
      required
    >
    </v-text-field>
          <v-text-field
            v-model="password"
            :append-icon="show1 ? 'visibility_off' : 'visibility'"
            :rules="passwordRule"
            :type="show1 ? 'text' : 'password'"
            name="input-10-1"
            label="password"
            hint="At least 6 characters"
            counter
             @click:append="show1 = !show1"
            required
          ></v-text-field>

          <v-text-field
            v-model="password_confirmation"
            :append-icon="show1 ? 'visibility_off' : 'visibility'"
            :rules="password_confirmationRule"
            :type="show1 ? 'text' : 'password_confirmation'"
            name="input-10-1"
            label="confrim password"
            hint="At least 6 characters"
            counter
            required
            @click:append="show1 = !show1"
          ></v-text-field>
        </v-card-text>
        <v-divider class="mt-5"></v-divider>
        <v-card-actions>
          <v-btn flat @click="clear">Cancel</v-btn>
          <v-spacer></v-spacer>
         
           <v-btn :disabled="!valid"  @click="submit"> submit</v-btn>
        </v-card-actions>
         </v-form>
      </v-card>
       
      </v-flex>
                  <v-subheader></v-subheader>

      
<v-flex xs12>
           <image-input v-model="avatar" @input="ReceiveImage">
        <div slot="activator">
          <v-avatar size="280px" v-ripple v-if="!avatar" class="grey lighten-3 mb-3">
            <span>Click to add avatar</span>
          </v-avatar>
          <v-avatar size="280px" v-ripple v-else class="mb-3">
            <img :src="avatar.imageURL" alt="avatar">
          </v-avatar>
        </div>
      </image-input>
      </v-flex>

   </v-layout>

    </v-container>
</template>
<script>
import Vue from "vue";
import VeeValidate from "vee-validate";
import ImageInput from "../Image/ImageInput.vue";
Vue.use(VeeValidate);
export default {
  $_veeValidate: {
    validator: "new"
  },
  components: {
    ImageInput: ImageInput
  },

  data: () => ({
    valid: true,
    name: "",
    avatar: { imageURL: "" },
    tempImageAsFile: null, // use this for image to send it to server
    show1: false,
    nameRules: [
      (v) => !!v || "Name is required",
      (v) => (v && v.length <= 30) || "Name must be less than 30 characters"
    ],
    email: "",
    emailRules: [
      (v) => !!v || "E-mail is required",
      (v) => /.+@.+/.test(v) || "E-mail must be valid"
    ],
    password: "",
    password_confirmation: "",
    passwordRule: [
      (v) => !!v || "Password is required",
      (v) => (v && v.length >= 6) || "Password must be more than 6 character"
    ],
    password_confirmationRule: [
      (v) => !!v || "password is required "
      //  v=> (v && this.password === v ) || 'the confirm password does not match with password',
    ]
  }),
  mounted() {
   this.setData();
  },
  computed: {
    nameUser: function() {
      return this.$store.getters.user.name;
    },
    //return the image for user
    imguserProfile: function() {
      return {
        imageURL:
          this.$store.getters.baseurl +
          "storage/" +
          this.$store.getters.user.avater
      };
    }
  },
  watch: {
    avatar: {
      handler: function() {
        this.saved = false;
      },
      deep: true
    }
  },

  methods: {
    setData(){
      // set old data for prfile , which it stored in browser
    this.avatar.imageURL = `${this.$store.getters.baseurl}storage/${this.$store.getters.user.avatar}`;
    this.name = this.$store.getters.user.name;
    this.email = this.$store.getters.user.email;
    this.password = this.$store.getters.user.password;
    this.password_confirmation = this.$store.getters.user.password;
    },
    submit() {
      this.$validator.validateAll().then(() => {
        var data = {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
          tempImageAsFile: this.tempImageAsFile,
          user_id: this.$store.getters.user.id
        };
        this.$store
          .dispatch("updateProfile", data)
          .then((res) => {
            if (res.data.success) {
              this.$toaster.success(res.data.success);
            } else if (res.data.error) {
              this.$toaster.error(res.data.error);
            } else {
              Object.values(res.data).forEach((element) => {
                for (let index = 0; index < element.length; index++) {
                  this.$toaster.error(element[index]);
                }
              });
            }
          })
          .catch((err) => {
           this.$toaster.error('Something happened error, try again or contact the support');
          });
      });
    },
    clear() {
      this.setData();
   //   this.$refs.form.reset();
     // this.avatar.imageURL = this.imguserProfile.imageURL;
    },
    ReceiveImage(file) {
      // receive image from Input image component
      this.tempImageAsFile = file.file;
    }
  }
};
</script>
<style>
.rounded-card {
  border-radius: 50%;
}
</style>
