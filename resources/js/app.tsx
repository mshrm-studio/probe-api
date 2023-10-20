import React from "react";
import ReactDOM from "react-dom/client";
import LilNounListFilters from "./LilNounListFilters";

const lilNounListFiltersEl = document.getElementById(
    "lil-noun-list-filters"
) as HTMLElement;
ReactDOM.createRoot(lilNounListFiltersEl).render(<LilNounListFilters />);
