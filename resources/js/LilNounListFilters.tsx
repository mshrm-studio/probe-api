import React, { useState, useEffect } from "react";

const LilNounListFilters: React.FC = () => {
    const params = new URLSearchParams(window.location.search);
    const initSearchValue = params.get("search");

    const [search, setSearch] = useState(initSearchValue ?? "");

    const handleInputChange = (event) => {
        setSearch(event.target.value);
    };

    const handleSubmit = (event) => {
        event.preventDefault(); // Prevent default form submission behavior

        // Construct the new URL with the updated search parameter
        const newURL = `${window.location.origin}${window.location.pathname}?search=${search}`;

        // Navigate to the new URL
        window.location.href = newURL;
    };

    return (
        <form
            style={{ textAlign: "center", marginBottom: 20 }}
            onSubmit={handleSubmit}
        >
            <input
                style={{ marginRight: 8 }}
                defaultValue={initSearchValue ?? undefined}
                type="text"
                name="search"
                id="search"
                placeholder="search"
                onChange={handleInputChange}
            />

            <button type="submit">Submit</button>
        </form>
    );
};

export default LilNounListFilters;
