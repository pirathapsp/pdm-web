( function( api ) {

	// Extends our custom "acmephoto" section.
	api.sectionConstructor['acmephoto'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );