import "./bootstrap";
import NotFound from './components/NotFound';
import { createApp } from "vue";
//Vue routes
import router from "./routes.js";

//Gate
import Gate from "./Gate.js";
//Pagination
import LaravelVuePagination from "laravel-vue-pagination";
//Progress Bar
import VueProgressBar from "@aacassandra/vue3-progressbar";
const options = {
    color: "#1df770",
    failedColor: "#881f1c",
    thickness: "5px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300,
    },
    autoRevert: true,
    location: "top",
    inverse: false,
};

//vform
import { Form } from "vform";
import {
    Button,
    HasError,
    AlertError,
    AlertErrors,
    AlertSuccess,
} from "vform/src/components/bootstrap5";
window.Form = Form;

//Date moment
import moment from "moment";

//Sweet alert
import Swal from "sweetalert2";
window.swal = Swal;
//Declare toast messages
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});
window.toast = Toast;

//init app
const app = createApp({});
//Global filters
app.config.globalProperties.$filters = {
    //Uppercase the first char
    upText(value) {
        if (!value) return "";
        value = value.toString();
        return value.charAt(0).toUpperCase() + value.slice(1);
    },
    //Date format
    dateFormat(value) {
        //return new Date(value).toLocaleString();
        return moment(value).format("MMMM Do YYYY, h:mm:ss a");
    },
    //Trancate a string
    stringTrancate(data, num) {
        // return value.substring(10, value.length);
        const reqdString = data.split("").slice(0, num).join("");
        return reqdString;
    },
};
//Get logged in user
//Set to global user
app.config.globalProperties.$gate = new Gate(window.user);
//console.log($gate);
app.use(router);
app.use(VueProgressBar, options);
app.component(Button.name, Button);
app.component(HasError.name, HasError);
app.component(AlertError.name, AlertError);
app.component(AlertErrors.name, AlertErrors);
app.component(AlertSuccess.name, AlertSuccess);
app.component("NotFound", NotFound);
app.component("Pagination", LaravelVuePagination);

app.mount("#app");
