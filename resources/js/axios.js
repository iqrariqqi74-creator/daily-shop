import axios from "axios";

axios.defaults.baseURL = "/api/"; // change this if you want to use a different url for APIs
axios.defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("admin_token");

export const IMAGE_URL = "http://127.0.0.1:8000/";
