$("#register").on("submit", async function (e) {
  e.preventDefault();

  let formData = new FormData(this);
  notyf.open({
    type: "info",
    message: "En cours...",
    duration: 90000,
  });
  try {
    const request = await axios.post(
      Routing.generate("app_register_new"),
      formData
    );
    const response = request.data;
    await notyf.dismissAll();
    console.log(response);
    notyf.open({
      type: "info",
      message: response,
    });
    window.location = Routing.generate("app_login");
  } catch (error) {
    console.log(error);
    const message = error.response.data;
    notyf.dismissAll();
    notyf.error(message);
  }
});
