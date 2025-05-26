export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig();

  const apiFetch = $fetch.create({
    baseURL: config.public.apiBaseUrl,
    // Le type est explicite ici pour éviter l'erreur
    onRequest({ options }: { options: any }) {
      const token = process.client ? localStorage.getItem('token') : null;

      // S’assurer que headers est un objet simple
      options.headers = {
        ...(options.headers || {}),
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
      };
    },
  });

  nuxtApp.provide('apiFetch', apiFetch);
});
