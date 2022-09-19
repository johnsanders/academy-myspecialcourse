(() => {
	if (!document.body.id === 'page-course-view-tiles') return;
	const idPattern = /(?:\?|&)id=([0-9]+)/;
	const match = window.location.search.match(idPattern);
	if (match?.length < 2) return;
	const courseId = match[1];
	const url = `/lib/ajax/service.php?sesskey=${window.M.cfg.sesskey}`;

	const run = async () => {
		addHiddenImportantClass();
		const tileEls = document.querySelectorAll('ul.tiles li.tile-clickable');
		const singleActivitySectionIds = [];
		for (const tileEl of tileEls) {
			const sectionId = tileEl.getAttribute("data-section");
			const sectionHtml = await getSectionHtml(sectionId);
			const [sectionNode] = $.parseHTML(sectionHtml)
			const numActivities = sectionNode.querySelectorAll('li.section ul.section li.activity').length;
			if (numActivities === 1) {
				const link = sectionNode.querySelector('li.section ul.section li.activity a')?.href;
				if (link) {
					singleActivitySectionIds.push(sectionId);
					tileEl.addEventListener('click', () => window.location.href = link);
				}
			}
		};
		hideSections(singleActivitySectionIds);
		removeHiddenImportantClass()
	};
	const addHiddenImportantClass = () => {
		document.querySelectorAll('li.section.main').forEach((el) => 
			el.classList.add('academyHiddenImportant'));
	}
	const removeHiddenImportantClass = () => {
		document.querySelectorAll('li.section.main').forEach((el) => 
			el.classList.remove('academyHiddenImportant'));
	}
	const getSectionHtml = async (sectionId) => {
		const body = [{
				args: { courseid: courseId, sectionid: sectionId },
				index: 0,
				methodname: "format_tiles_get_single_section_page_html",
		}];
		const opts = {
			body: JSON.stringify(body),
			headers: { "Content-Type": "application/json" },
			method: "POST",
		};
		const res = await fetch(url, opts);
		const json = await res.json();
		return json[0].data.html;
	};
	const hideSections = (sectionIds) => {
		const styles = sectionIds.map((sectionId) => 
			document.createTextNode(`#section-${sectionId} { display: none !important; }`)
		);
		const styleEl = document.createElement('style');
		styles.forEach((style) => styleEl.appendChild(style));
		document.querySelector('head')?.appendChild(styleEl);
	};
	if (document.readyState !== 'loading') run();
	else document.addEventListener('DOMContentLoaded', run)
})()
