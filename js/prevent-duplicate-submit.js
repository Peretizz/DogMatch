;(() => {
  let isSubmitting = false

  // Prevent duplicate message submissions
  document.addEventListener("submit", (e) => {
    if (e.target.id === "message-form" || e.target.classList.contains("message-form")) {
      if (isSubmitting) {
        e.preventDefault()
        return false
      }
      isSubmitting = true
      setTimeout(() => {
        isSubmitting = false
      }, 1000)
    }
  })

  // Prevent duplicate comment submissions
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("comentario-enviar")) {
      if (isSubmitting) {
        e.preventDefault()
        return false
      }
      isSubmitting = true
      setTimeout(() => {
        isSubmitting = false
      }, 1000)
    }
  })

  // Prevent Enter key spam
  document.addEventListener("keypress", (e) => {
    if (
      e.key === "Enter" &&
      (e.target.classList.contains("message-input") || e.target.classList.contains("comentario-input"))
    ) {
      if (isSubmitting) {
        e.preventDefault()
        return false
      }
      isSubmitting = true
      setTimeout(() => {
        isSubmitting = false
      }, 1000)
    }
  })
})()
