require("./bootstrap");

import * as FilePond from "filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginImageResize from "filepond-plugin-image-resize";
import FilePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation";

window.FilePond = FilePond;
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginImageExifOrientation
);

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
