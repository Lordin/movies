
	export function axiosCatch (error) {
    if (error && error.response) {
      if (error.response.status == 422) {
        var formErrors = Object.values(error.response.data.errors);
        for (var i=0; i<formErrors.length; i++) {
          Vue.toasted.show(formErrors[i][0], {
            type: 'error',
            duration: '5000',
          });
        }
      }
      if (error.response.status == 429) {
        Vue.toasted.show(error.response.data.message, {
          type: 'error',
          duration: '8000',
        });
      }
      if (error.response.status == 419) {
        location.reload();
      }
      if (error.response.status == 401) {
        location.reload();
      }
    }
	}