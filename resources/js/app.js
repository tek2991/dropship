require("./bootstrap");

import * as FilePond from "filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginImageResize from "filepond-plugin-image-resize";
import FilePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation";

import "flowbite";

window.FilePond = FilePond;
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginImageExifOrientation
);

// import Splide from "@splidejs/splide";
// window.Splide = Splide;

import { Fancybox } from "@fancyapps/ui";
window.Fancybox = Fancybox;

import 'tw-elements';

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
