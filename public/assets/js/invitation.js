(() => {
    const guestNameElement = document.getElementById("invitation-guest-name");
    const token = new URLSearchParams(window.location.hash.slice(1)).get("token");

    if (!guestNameElement || !token || token.length > 128) {
        return;
    }

    fetch(new URL("api/invitation", document.baseURI), {
        headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Could not load invitation guest (${response.status}).`);
            }

            return response.json();
        })
        .then((guest) => {
            if (typeof guest.name === "string" && guest.name.trim() !== "") {
                guestNameElement.textContent = guest.name;
            }
        })
        .catch((error) => {
            console.error("Failed to load personalized invitation.", error);
        });
})();
