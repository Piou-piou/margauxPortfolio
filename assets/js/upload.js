import qq from 'fine-uploader/lib/all';
import $ from 'jquery'

const ajaxScript = document.getElementById('ajax-script');
let { currentScript } = document;

if (ajaxScript !== null) {
  currentScript = ajaxScript;
}
const multiple = currentScript.getAttribute('multiple');
const endpoint = currentScript.getAttribute('endpoint');
const endpointSession = currentScript.getAttribute('endpoint-session');
const extensionsStr = currentScript.getAttribute('extensions');
let extensions = [];
if (extensionsStr !== null && extensionsStr !== '') {
  extensions = extensionsStr.split(',').map(item => item.trim());
}

// main selectors of elements
const uploader = '.qq-gallery.qq-uploader';
const uploaderArea = '.qq-upload-drop-area-selector.qq-upload-drop-area';
const uploaderShowArea = '.qq-show-area';

/**
 * init FineUploader
 */
function galleryUploader() {
  if (!document.getElementById('fine-uploader-gallery')) return;

  const galleryUploader = new qq.FineUploader({
    element: document.getElementById('fine-uploader-gallery'),
    template: 'qq-template-gallery',
    session: {
      endpoint: endpointSession,
    },
    request: {
      endpoint,
    },
    validation: {
      allowedExtensions: extensions,
    },
    autoUpload: true,
    deleteFile: {
      enabled: true,
      forceConfirm: true,
      endpoint,
      confirmMessage: 'Êtes-vous sûr(e) de vouloir SUPPRIMER ce fichier : {filename}?',
    },
    messages: {
      typeError: '{file} a une extension invalide. Extension(s) autorisées : {extensions}.',
      sizeError: '{file} est trop volumineux, la taille maximale est de {sizeLimit}.',
      minSizeError: '{file} est trop petit, la taille minimale est de {minSizeLimit}.',
      emptyError: '{file} est vide, merci de renvoyer votre fichier.',
      noFilesError: 'Aucun fichier à envoyer',
      tooManyItemsError: 'Il y a trop de fichiers ({netItems}) a envoyer.  Le nombre de fichiers maximum est {itemLimit}.',
      maxHeightImageError: 'La taille de l\'image est trop grande',
      maxWidthImageError: 'L\'image est trop large',
      minHeightImageError: 'L\'image n\'est pas assez grande',
      minWidthImageError: 'L\'image n\'est pas assez large',
      retryFailTooManyItems: 'La nouvelle tentative a échoué. Vous avez atteint la limite du nombre de fichiers',
      onLeave: 'Les fichiers sont en cours de téléchargement. Si vous quittez cette page maintenant, le téléchargement sera annulé.',
      unsupportedBrowserIos8Safari: 'Votre navigateur n\'est pas supporté',
      tooManyFilesError: 'Vous ne pouvez envoyer qu\'un seul fichier',
      unsupportedBrowser: 'Votre navigateur n\'est pas supporté',
    },
    multiple: (multiple === 'true'),
    callbacks: {
      onSessionRequestComplete: (data) => {
        document.getElementById('input-fine-uploader-delete').value = '[]';
        const input = document.getElementById('input-fine-uploader');
        input.value = '[]';

        for (let i = 0; i < data.length; i += 1) {
          const extension = data[i].thumbnailUrl.split('.')[1];

          const value = JSON.parse(input.value);
          value.push(`${data[i].uuid}.${extension}`);

          input.value = JSON.stringify(value);
        }
      },
      onStatusChange: (id, oldStatus, newStatus) => {
        if (newStatus === 'deleting') {
          $('button.qq-upload-delete-selector').css('display', 'none');
        } else {
          $('button.qq-upload-delete-selector').css('display', 'initial');
        }
      },
      onComplete: (id, name, data) => {
        const input = document.getElementById('input-fine-uploader');
        const inputRealNames = document.getElementById('input-fine-uploader-real-names');
        const { uploadName } = data;
        let valuesRealName = {};

        if (multiple === 'true' || multiple === true) {
          if (input.value === '') {
            input.value = JSON.stringify([uploadName]);
          } else {
            const value = JSON.parse(input.value);
            value.push(uploadName);
            input.value = JSON.stringify(value);
          }
          if (inputRealNames.value === '') {
            valuesRealName[uploadName] = name;
            inputRealNames.value = JSON.stringify(valuesRealName);
          } else {
            valuesRealName = JSON.parse(inputRealNames.value);
            valuesRealName[uploadName] = name;
            inputRealNames.value = JSON.stringify(valuesRealName);
          }
        } else {
          if (input.value !== '') {
            const inputDelete = document.getElementById('input-fine-uploader-delete');
            const valuesDelete = JSON.parse(inputDelete.value);

            const values = JSON.parse(input.value);

            for (let i = 0; i < values.length; i += 1) {
              valuesDelete.push(values[i]);
            }

            inputDelete.value = JSON.stringify(valuesDelete);
          }

          input.value = JSON.stringify([uploadName]);
          valuesRealName[uploadName] = name;
          inputRealNames.value = JSON.stringify(valuesRealName);
        }

        const event = new Event('change');
        input.dispatchEvent(event);
      },
      onDeleteComplete: (id, xhr) => {
        const input = document.getElementById('input-fine-uploader');
        const inputRealNames = document.getElementById('input-fine-uploader-real-names');
        const values = JSON.parse(input.value);
        const valuesRealName = JSON.parse(inputRealNames.value);

        const valueToDelete = values[id];
        values.splice(values[id], 1);
        delete valuesRealName[values[id]];

        input.value = JSON.stringify(values);
        inputRealNames.value = JSON.stringify(valuesRealName);

        const data = JSON.parse(xhr.response);

        if (data.fichier_serveur === 'true' || data.fichier_serveur === true) {
          const inputDelete = document.getElementById('input-fine-uploader-delete');
          const valuesDelete = JSON.parse(inputDelete.value);

          valuesDelete.push(valueToDelete);
          inputDelete.value = JSON.stringify(valuesDelete);
        }
      },
    },
  });

  // init empty class vide sur .qq-gallery.qq-uploader to show icon
  $(uploader).addClass('vide');
}

/**
 * gère le style de la zone au survol
 */
function styleZoneUploader() {

  $(uploaderArea).on('dragenter', () => {
    $(uploaderShowArea).removeClass('hidden');
  });

  $(uploaderArea).on('dragleave', () => {
    $(uploaderShowArea).addClass('hidden');
  });

  $(uploaderArea).on(', drop', () => {
    $(uploaderShowArea).addClass('hidden');
  });
}

/**
 * vérifie si l'élément que l'on drag est un fichier
 * @param dragEvent
 * @returns {*}
 */
function isFileDrag(dragEvent) {
  let fileDrag;
  qq.each(dragEvent.originalEvent.dataTransfer.types, (key, val) => {
    if (val === 'Files') {
      fileDrag = true;
      return false;
    }
  });
  return fileDrag;
}

/**
 * gère l'affichage de l'icone dans la zone d'upload
 */
function affichageIcon() {
  $(uploaderArea).on('drop', (event) => {
    if (isFileDrag(event)) {
      event.preventDefault();
      if (extensions.length === 0) {
        $(uploader).removeClass('vide');
      } else {
        $(event.originalEvent.dataTransfer.files).each((key, val) => {
          const formats = val.name.split('.');
          const format = formats[formats.length - 1];
          if (extensions.indexOf(format) !== -1) {
            $(uploader).removeClass('vide');
            return false;
          }
        });
      }
    }
  });

  $('input[name="qqfile"]').on('change', (event) => {
    if ($(event.currentTarget).val() === []) {
      $(uploader).addClass('vide');
    } else {
      $(uploader).removeClass('vide');
    }
  });
}

function initGallery() {
  galleryUploader();
  affichageIcon();
  styleZoneUploader();
}

function initGalleryPopup() {
  const files = $('#files');
  if ($(files) !== null && $('#input-fine-uploader-real-names') !== null) {
    $('#input-fine-uploader-real-names').val($(files).val());
  }
  initGallery();
}

$(document).ready(() => {
  if (ajaxScript === null) {
    const files = $('#files');
    console.log(files);
    if ($(files) !== null && $('#input-fine-uploader-real-names') !== null) {
      $('#input-fine-uploader-real-names').val($(files).val());
    }
    initGallery();
  }
});

export { initGalleryPopup };
export default initGallery;