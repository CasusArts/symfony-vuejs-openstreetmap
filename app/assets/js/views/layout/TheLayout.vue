<template>
  <v-app>
    <TheHeader/>
    <TheNavigation/>
    <TheContent/>
    <NotificationBar/>
    <DialogLoader/>
    <DialogPopup />
  </v-app>
</template>

<script>
import TheHeader from './TheHeader.vue';
import TheNavigation from './TheNavigation.vue';
import TheContent from './TheContent.vue';
import NotificationBar from '../../components/NotificationBar.vue';
import DialogLoader from '../../components/DialogLoader.vue';
import DialogPopup from '../../components/DialogPopup.vue';

export default {
  name: 'TheLayout',
  components: {
    TheHeader,
    TheNavigation,
    TheContent,
    NotificationBar,
    DialogLoader,
    DialogPopup,
  },
};
</script>
