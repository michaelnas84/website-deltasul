const editor = new EditorJS({
  holder: 'editorjs',
  minHeight: 0,
  /** 
     * Available Tools list. 
     * Pass Tool's class or Settings object for each Tool you want to use 
     */
  tools: {
    // header: Header,
    // delimiter: Delimiter,
    paragraph: {
      class: Paragraph,
      inlineToolbar: true,
    },
    embed: Embed,
    image: {
      class: ImageTool,
      config: {
        uploader: {
          async uploadByFile(file) {
            let photo = file;
            let formData = new FormData();
            formData.append("acao", 'cadastrar_blog_imagem');
            formData.append("photo", photo);
            var nome_arq = fetch('includes/central_controller.php', { method: "POST", body: formData })
              .then(async response => {
                try {
                  const retorno = await response.text()
                  return retorno
                } catch (error) {
                  console.error(error)
                  return false
                }
              })
            // var reader = new FileReader();
            // reader.onloadend = function() {
            // // console.log(reader.result)
            // }
            // reader.readAsDataURL(file);
            return {
              success: 1,
              file: {
                url: `../img_base/blog/${await nome_arq}`
              }
            }
          }
        }
      }
    },
    textVariant: TextVariantTune,
    // footnotes: {
    //   class: FootnotesTune,
    // },
    // Marker: {
    //   class: Marker,
    //   shortcut: 'CMD+SHIFT+M',
    // },
    // warning: Warning,
    // inlineCode: {
    //   class: InlineCode,
    //   shortcut: 'CMD+SHIFT+M',
    // },
    // delimiter: Delimiter,
    // quote: Quote,
    // code: CodeTool,
    // list: {
    //   class: NestedList,
    //   inlineToolbar: true,
    // },
    // checklist: {
    //   class: Checklist,
    //   inlineToolbar: true,
    // },
    // raw: RawTool,
    underline: Underline,
    // header: Header,
    paragraph: {
      class: Paragraph,
      inlineToolbar: true,
    },
    // attaches: {
    //   class: AttachesTool,
    //   config: {
    //     endpoint: 'http://localhost:8008/uploadFile'
    //   }
    // },
    // link: {
    //   class: LinkAutocomplete,
    //   config: {
    //     endpoint: 'http://localhost:3000/',
    //     queryParam: 'search'
    //   }
    // },
    // embed: Embed,
    // personality: {
    //   class: Personality,
    //   config: {
    //     endpoint: 'http://localhost:8008/uploadFile'  // Your backend file uploader endpoint
    //   }
    // },
    // table: {
    //   class: Table,
    //   inlineToolbar: true,
    //   config: {
    //     rows: 2,
    //     cols: 3,
    //   },
    // },
    // carousel: {
    //   class: Carousel,
    //   config: {
    //     uploader: {
    //       async uploadByFile(file) {
    //         let photo = file;
    //         let formData = new FormData();
    //         formData.append("acao", 'cadastrar_blog_imagem');
    //         formData.append("photo", photo);
    //           var nome_arq = fetch('includes/central_controller.php', {method: "POST", body: formData})
    //           .then(async response => {
    //             try {
    //                 const retorno = await response.text()
    //                 return retorno
    //             } catch (error) {
    //                 console.error(error)
    //                 return false
    //             }
    //           })
    //         // var reader = new FileReader();
    //         // reader.onloadend = function() {
    //         // // console.log(reader.result)
    //         // }
    //         // reader.readAsDataURL(file);
    //         return {
    //           success: 1,
    //           file: {
    //             url: `../img_base/blog/${await nome_arq}`
    //           }
    //         }
    //       }
    //     }
    //   }
    // },
  },
  tunes: ['textVariant'],
}
)

function exibe_notificacao(svg, txt_1, txt_2){
  if(svg == 'red'){
      svg = '<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
  } else {
      svg = '<svg class="h-6 w-6 text-green-400" x-description="Heroicon name: outline/check-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
  }
  $("#notification").click()
  $("#notification_svg").html(svg)
  $("#notification_txt_1").html(txt_1)
  $("#notification_txt_2").html(txt_2)
}

function envia_form() {
  editor.save().then((savedData) => {
    const edjsParser = edjsHTML()
    let html = edjsParser.parse(savedData)
    console.log(html)
    var conteudo = html
    cadastrar_blog_post(conteudo)
  }).catch((error) => {
    console.log(error)
  })
}

function cadastrar_blog_post(conteudo) {
  let formData = new FormData()
  formData.append("acao", "cadastrar_blog_post")
  formData.append("titulo", $('#titulo').val())
  formData.append("conteudo", JSON.stringify(conteudo))

  fetch('includes/central_controller.php', { method: "POST", body: formData })
    .then(async response => {
      try {
        const retorno = await response.text()
        console.log(retorno)
        if (retorno == 'error_user') {
          exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
          setTimeout(() => window.location.reload(), 2000)
        } else if (retorno == 'success') {
          exibe_notificacao('green', 'Post cadastrado com sucesso!', 'A página será recarregada')
          setTimeout(() => window.location.replace('dashboard_blog.php'), 2000)
        }
      } catch (error) {
        console.error(error)
        return false
      }
    })
}