(function () {
  function adaptQRCodeLibrary(callback) {
    if (typeof QRCode === 'undefined') {
      setTimeout(function () { adaptQRCodeLibrary(callback); }, 100);
      return;
    }
    if (window.QRCodeAdapterReady) {
      callback();
      return;
    }

    var OriginalQRCode = QRCode;
    window.QRCode = {
      toString: function (text, options, cb) {
        try {
          var div = document.createElement('div');
          div.style.position = 'absolute';
          div.style.left = '-9999px';
          document.body.appendChild(div);

          new OriginalQRCode(div, {
            text: text,
            width: options.width || 300,
            height: options.width || 300,
            colorDark: options.color.dark || '#000000',
            colorLight: options.color.light || '#FFFFFF',
            correctLevel: OriginalQRCode.CorrectLevel.M,
          });

          setTimeout(function () {
            var canvas = div.querySelector('canvas');
            if (canvas) {
              var svg = canvasToSVG(canvas, options.color.dark || '#000000', options.color.light || '#FFFFFF');
              document.body.removeChild(div);
              cb(null, svg);
            } else {
              document.body.removeChild(div);
              cb(new Error('Failed to generate QR code'));
            }
          }, 100);
        } catch (error) {
          cb(error);
        }
      },
    };
    window.QRCodeAdapterReady = true;
    callback();
  }

  function canvasToSVG(canvas, darkColor, lightColor) {
    var width = canvas.width;
    var height = canvas.height;
    var ctx = canvas.getContext('2d');
    var imageData = ctx.getImageData(0, 0, width, height);
    var data = imageData.data;
    var svg = '<svg width="' + width + '" height="' + height + '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' + width + ' ' + height + '">';
    svg += '<rect width="' + width + '" height="' + height + '" fill="' + lightColor + '"/>';
    var blockSize = Math.max(1, Math.floor(width / 100));
    for (var y = 0; y < height; y += blockSize) {
      for (var x = 0; x < width; x += blockSize) {
        var idx = (y * width + x) * 4;
        if (idx < data.length && data[idx] < 128) {
          var w = Math.min(blockSize, width - x);
          var h = Math.min(blockSize, height - y);
          svg += '<rect x="' + x + '" y="' + y + '" width="' + w + '" height="' + h + '" fill="' + darkColor + '"/>';
        }
      }
    }
    svg += '</svg>';
    return svg;
  }

  function applyFrameToSVG(svg, frameConfig, size) {
    var frameType = frameConfig.type;
    var frameColor = frameConfig.color || '#000000';
    var frameSvg = '';
    switch (frameType) {
      case 'square':
        frameSvg = '<rect x="0" y="0" width="' + size + '" height="' + size + '" fill="none" stroke="' + frameColor + '" stroke-width="4"/>';
        break;
      case 'rounded':
        frameSvg = '<rect x="0" y="0" width="' + size + '" height="' + size + '" rx="20" ry="20" fill="none" stroke="' + frameColor + '" stroke-width="4"/>';
        break;
      case 'circle':
        var center = size / 2;
        frameSvg = '<circle cx="' + center + '" cy="' + center + '" r="' + (center - 2) + '" fill="none" stroke="' + frameColor + '" stroke-width="4"/>';
        break;
      case 'badge':
        frameSvg = '<rect x="0" y="0" width="' + size + '" height="' + size + '" rx="15" ry="15" fill="none" stroke="' + frameColor + '" stroke-width="4"/>';
        break;
    }
    return svg.replace('</svg>', frameSvg + '</svg>');
  }

  function readConfig(form) {
    var data = new FormData(form);
    return {
      pattern: data.get('pattern') || 'square',
      eyes: data.get('eyes') || 'square',
      frame: {
        type: data.get('frame_type') || 'none',
        text: data.get('frame_text') || '',
        color: data.get('frame_color') || '#000000',
        text_color: data.get('frame_text_color') || '#000000',
        text_size: parseInt(data.get('frame_text_size') || 14, 10),
        bg_enabled: data.get('frame_bg_enabled') === 'on' || data.get('frame_bg_enabled') === '1',
        bg_color: data.get('frame_bg_color') || '#FFFFFF',
      },
      colors: {
        foreground: data.get('foreground_color') || '#000000',
        background: data.get('background_color') || '#FFFFFF',
      },
      logo: {
        enabled: data.get('logo_enabled') === 'on' || data.get('logo_enabled') === '1',
        size: parseFloat(data.get('logo_size') || 0.2),
      },
    };
  }

  function generateQRPreview(container, config, textContainer) {
    if (typeof QRCode === 'undefined' || typeof QRCode.toString !== 'function') {
      adaptQRCodeLibrary(function () { generateQRPreview(container, config, textContainer); });
      return;
    }

    container.innerHTML = '<p class="preview-placeholder">Generating preview...</p>';
    QRCode.toString('https://example.com/test-qr-preview', {
      type: 'svg',
      width: 300,
      margin: 2,
      color: { dark: config.colors.foreground, light: config.colors.background },
      errorCorrectionLevel: 'M',
    }, function (err, svg) {
      if (err || !svg) {
        container.innerHTML = '<p class="preview-error">Error generating QR code</p>';
        return;
      }

      var styledSvg = config.frame.type !== 'none' ? applyFrameToSVG(svg, config.frame, 300) : svg;
      container.innerHTML = styledSvg;

      if (textContainer) {
        if (config.frame.text && config.frame.text.trim()) {
          textContainer.textContent = config.frame.text;
          textContainer.style.display = 'block';
          textContainer.style.color = config.frame.text_color || '#000000';
          textContainer.style.fontSize = (config.frame.text_size || 14) + 'px';
          textContainer.style.fontWeight = '600';
          textContainer.style.textAlign = 'center';
          textContainer.style.width = '300px';
          textContainer.style.padding = '8px 0';
          textContainer.style.backgroundColor = config.frame.bg_enabled ? (config.frame.bg_color || '#FFFFFF') : 'transparent';
          textContainer.style.borderRadius = config.frame.bg_enabled ? '4px' : '0';
        } else {
          textContainer.style.display = 'none';
        }
      }
    });
  }

  window.toggleFrameOptions = function () {
    var form = document.getElementById('qrTemplateForm');
    if (!form) return;
    var frameType = form.querySelector('[name="frame_type"]').value;
    var el = document.getElementById('frameOptions');
    if (el) el.style.display = frameType !== 'none' ? 'block' : 'none';
  };

  window.toggleFrameBgOptions = function () {
    var cb = document.getElementById('frame_bg_enabled');
    var el = document.getElementById('frameBgOptions');
    if (cb && el) el.style.display = cb.checked ? 'block' : 'none';
  };

  window.toggleLogoOptions = function () {
    var cb = document.getElementById('logo_enabled');
    var el = document.getElementById('logoOptions');
    if (cb && el) el.style.display = cb.checked ? 'block' : 'none';
  };

  window.updateQrPreview = function () {
    var form = document.getElementById('qrTemplateForm');
    if (!form) return;
    generateQRPreview(
      document.getElementById('qrPreview'),
      readConfig(form),
      document.getElementById('qrPreviewText')
    );
  };

  window.initQrTemplateEditor = function (formId, previewId, textId) {
    var form = document.getElementById(formId);
    if (!form) return;
    adaptQRCodeLibrary(function () {
      generateQRPreview(
        document.getElementById(previewId),
        readConfig(form),
        document.getElementById(textId)
      );
    });
  };
})();
