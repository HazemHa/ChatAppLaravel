<template>
  <v-form ref="form" v-model="valid" lazy-validation>
    <v-text-field v-model="name" :rules="nameRules" :counter="10" label="Name" required></v-text-field>
    <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
    <v-text-field
      v-model="password"
      :append-icon="show1 ? 'visibility_off' : 'visibility'"
      :rules="passwordRule"
      :type="show1 ? 'text' : 'password'"
      name="input-10-1"
      label="password"
      hint="At least 8 characters"
      counter
      required
    ></v-text-field>

    <v-text-field
      v-model="password_confirmation"
      :append-icon="show1 ? 'visibility_off' : 'visibility'"
      :rules="password_confirmationRule"
      :type="show1 ? 'text' : 'password'"
      name="input-10-1"
      label="confrim password"
      hint="At least 8 characters"
      counter
      required
      @click:append="show1 = !show1"
    ></v-text-field>

    <v-btn :disabled="!valid" @click="submit">submit</v-btn>
  </v-form>
</template>
<script>
export default {
  data: () => ({
    valid: true,
    name: "",
    show1: false,
    nameRules: [
      v => !!v || "Name is required",
      v => (v && v.length <= 10) || "Name must be less than 10 characters"
    ],
    email: "",
    emailRules: [
      v => !!v || "E-mail is required",
      v => /.+@.+/.test(v) || "E-mail must be valid"
    ],
    password: "",
    password_confirmation: "",
    passwordRule: [
      v => !!v || "Password is required",
      v => (v && v.length >= 6) || "Password must be more than 6 character"
    ],
    password_confirmationRule: [
      v => !!v || "password is required "
      //  v=> (v && this.password === v ) || 'the confirm password does not match with password',
    ]
  }),
  created() {},

  methods: {
    submit() {
      if (this.$refs.form.validate()) {
        this.$store
          .dispatch("register", {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation
          })
          .then(res => {
            this.$router.push({name:"home"});
          })
          .catch(err => {
            console.log("err", res);
          });
      }
    },
    clear() {
      this.$refs.form.reset();
    }
  }
};
</script>
