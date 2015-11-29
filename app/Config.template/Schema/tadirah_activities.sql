/*!40000 ALTER TABLE `tadirah_activities` DISABLE KEYS */;
INSERT INTO `tadirah_activities` (`id`, `parent_id`, `name`, `description`) VALUES
	(1, 59, 'Capture', 'Capture generally refers to the activity of creating digital surrogates of existing cultural artefacts, or expressing existing artifacts in a digital representation (digitization). This could be a manual process (as in "transcribing") or an automated procedure (as in "imaging" or "data_recognition"). Such capture precedes "enrichment" and "analysis", at least from a systematic point of view, if not in practice. '),
	(2, 59, 'Creation', 'Creating things generally refers to the activity of producing born-digital digital objects, rather than creating digital objects by capturing and digitizing existing analog objects. Creating can involve writing natural language text (cf. "writing") or, understood more broadly as the creation of a string of discrete symbols, it could also concern other forms of expression, such as creating executable code (cf. "programming"), composing a musical score, or creating an image.'),
	(3, 59, 'Enrichment', 'Enrichment refers to the activity of adding information to an object of enquiry, by making its origin, nature, structure, meaning, or elements explicit. This activity typically follows the capture of the object. '),
	(4, 59, 'Analysis', 'This general research goal refers to the activity of extracting any kind of information from open or closed, structured or unstructured collections of data, of discovering recurring phenomena, units, elements, patterns, groupings, and the like. This can refer to structural, formal or semantic aspects of data. Analysis also includes methods used to visualize results. Methods and techniques related to this goal may be considered to follow "capture" and "enrichment"; however, "enrichment" depends upon assumptions, research questions and results related to "analysis". '),
	(5, 59, 'Interpretation', 'Interpretation is the activity of ascribing meaning to phenomena observed in "analysis". Therefore, interpretation usually follows analysis, although it could also be considered that interpretation defines the hermeneutic perspective of any method of analysis. '),
	(6, 59, 'Storage', 'Storing refers to the activity of making digital copies of objects of inquiry, results of research, or software and services and of keeping them accessible, without necessarily making them available to the public. '),
	(7, 59, 'Dissemination', 'Dissemination refers to the activity of making objects of inquiry, results of research, or software and services available to fellow researchers or the wider public in a variety of more or less formal ways. It builds on or requires storing and can include releasing and sharing of data using a variety of methods and techniques including the application of linked open data.'),
	(9, 1, 'conversion', 'Conversion refers to changing the file format of an object (e.g. converting a .wmv video to a .mov file as well as converting VHS into a digital format) without fundamentally changing the content or nature of the object. When conversion concerns metadata, it involves mapping one metadata schema to another. More fundamental “conversions”, such as converting a scanned page image to an editable text document, are better referred to using “DataRecognition”.'),
	(10, 1, 'DataRecognition', 'Data Recognition, for example OCR, refers to the process of treating the immediate products of digital data capture (recording or imaging), such as digital facsimiles of texts or of sheet music, in a way to extract discrete, machine-readable units from them, such as plain text words, musical notes, or still or moving image elements (including, for example, face recognition).'),
	(11, 1, 'Discovering', 'Discovering is the activity of seeking out objects of research, research results, or other information which is useful in a given search perspective. Discovery includes very directed techniques such as advanced querying of databases, less directed techniques such as simple searching, and more serendipitous ones as browsing, which would include faceted browsing. (It is different from Information Retrieval, which is a structured way of extracting some piece of information or some specific subset of objects from a resource.)'),
	(12, 1, 'Gathering', 'Gathering refers to aggregating discovered resources, usually in some structured way (e.g. bringing together all papers that address a certain topic, as part of a literature review, or pulling all works by a particular author out of a digital collection for further analysis). Related techniques include web crawling and scraping.'),
	(13, 1, 'Imaging', 'Imaging refers to the capture of texts, images, artefacts or spatial formations using optical means of capture. Imaging can be made in 2D or 3D, using various means (light, laser, infrared, ultrasound). Imaging usually does not lead to the identification of discrete semantic or structural units in the data, such as words or musical notes, which is something DataRecognition accomplishes. Imaging also includes scanning and digital photography.'),
	(14, 1, 'Recording', 'Capturing audio and/or video; the result is a digital audio (e.g. WAV, MP3, etc.) or video (e.g. MP4, Quicktime, etc.) file that can be manipulated, analyzed, and/or stored.'),
	(21, 1, 'Transcription', 'Transcription is the activity of creating a representation of a manuscript (often in combination with Enrichment) or of audio or video recordings. The representation is (also) generally textual for the verbal aspects of recordings and structured for example by speech turns, but can also contain multimodal information like gestures or events and multimedia information like time synchronization and relation to media files. Transcription that is partial, selective, and/or inherently linked to the source document may be better categorized as Annotation.'),
	(22, 2, 'Designing', 'The development of a user interface, with which the user is able to interact to perform various tasks and conduct activities. Also included here is the development of the user experience, where a person’s perceptions of the practical aspects such as utility, ease of use, and efficiency of the system are taken into consideration. Does not include the implementation of the design (see “Programming” or “Web development”). Database design is to be categorized using “Modeling”.'),
	(23, 2, 'Programming', 'Creation of code executable by a computer, that is creation of scripts or software. (This includes “Prototyping”, the creation of such code for testing or modeling purposes.) It is also closely related to the more broader activity of tool development. Programming is separate from Encoding (enriching a document by making structural, layout-related, semantic, or other information about a specific part of a document explicit by adding markup to its transcription).'),
	(24, 2, 'Translation', 'Translation involves creating a new linguistic object based on a source document but written in a different language than the source. This applies to both natural languages and machine-readable programming languages'),
	(25, 2, 'Web development', 'Creation of websites, by building on a platform (e.g. content management systems such as Drupal, WordPress and Omeka) or writing HTML/CSS. Writing a module/plugin for a platform, or programming web-based applications, should use the “Programming” method.'),
	(26, 2, 'Writing', 'Writing designates the activity of creating new texts (instead of capturing existing text). In our context, this would primarily concern research papers and reports, but may include other textually-oriented objects.'),
	(27, 3, 'Annotating', 'Annotating refers to the activity of making information about a digital object explicit by adding, e.g., comments, metadata or keywords to a digitized representation or to an annotation file associated with it. This can be in the form of annotations that comment on or contextualize a passage (explanatory annotations) in order to make structural or linguistic information explicit (structural/linguistic annotation), as linked open data making the relationships between objects machine-readable, or, in the case of general metadata, adding information about the object as a whole. Encoding is a technique associated with annotating, as are POS-Tagging, Tree-Tagging, and Georeferencing.'),
	(28, 3, 'Clean up', 'Data cleanup involves improving the quality of an existing digital object. This could include such things as correcting errors in a written text, errors in OCR results, debugging code, improving the quality of video, audio, or image file'),
	(29, 3, 'Editing', 'Editing refers to making structural, layout-related, semantic, or other information about a specific part of a document explicit by adding (inline or stand-off) markup to its transcription. This is typically part of the larger activity of scholarly editing of textual, musical, or other sources. It is based on a transcription of the document (the result of data recognition) and guided by a model of the document (the result of modeling).'),
	(30, 4, 'Content Analysis', 'Content Analysis is a method which aims to analyse aspects of digital objects relating to their meaning, such as identifying concepts or meaningful units. Relevant techniques include Topic Modeling, Sentiment Analysis, Information Retrieval, Discourse Analysis, but also Named Entity Recognition.'),
	(31, 4, 'Network Analysis', 'Network Analysis is a method to study the relations of (real or fictional) actors or other entities in a mediated network, which can take the form of a social or academic online network, a set of correspondence, or a work of literature; the resulting network is usually made up of nodes (entities) and edges (relations). Relevant techniques include Named Entity Recognition. When the artefacts themselves (texts, images, etc.) and their relations are concerned, the corresponding research activity would be Relational Analysis.'),
	(32, 4, 'Relational Analysis', 'Relational Analysis refers to computational techniques serving to discover specific relations between several objects of study. In textual studies, this could mean discovering overlap between several different texts (study of text reuse / plagiarism), or textual variations between several versions of one text (collation), or assessing the similarity of texts in terms of stylistic features (stylometry). By analogy, such methods can also be applied to other cultural artefacts, such as music, film or painting. Relevant techniques include Sequence Alignment, Collation, and techniques associated with Stylistic Analysis.'),
	(33, 4, 'Spatial Analysis', 'Relational Analysis refers to computational techniques serving to discover specific relations between several objects of study. In textual studies, this could mean discovering overlap between several different texts (study of text reuse / plagiarism), or textual variations between several versions of one text (collation), or assessing the similarity of texts in terms of stylistic features (stylometry). By analogy, such methods can also be applied to other cultural artefacts, such as music, film or painting. Relevant techniques include Sequence Alignment, Collation, and techniques associated with Stylistic Analysis.'),
	(37, 4, 'Structural Analysis', 'Structural Analysis involves analysis of objects on the level of the relations between structural elements of a cultural artefact (level of morphology or syntax in linguistics). Relevant techniques include: POS-Tagging, Tree-Tagging, Collocation Analysis, Concordancing.'),
	(38, 4, 'Stylistic Analysis', 'Stylistic Analysis consists of identifying stylistic or formal features of digital objects. Although computational stylistics is in many cases applied to texts and based on linguistic features, it can also be applied to other media such as physical artifacts, painting, music or movies. Relevant techniques include: Stylometry, Principal Component Analysis, Cluster Analysis, Paleographic Analysis.'),
	(39, 4, 'Visualization', 'Visualization refers to activities which serve to summarise and present in a graphical form, and to use such graphical forms analytically, that is to detect patterns, structures, or points of interest in the underlying data. Virtually any kind of data can be visualized, and the forms of visualizations can be images, maps, timelines, graphs, or tables, and the like. Relevant techniques include plotting and mapping.'),
	(40, 5, 'Contextualizing', 'Contextualization is the activity of creating associations between an object of investigation and other, more established or better-understood objects in a relation of geographical, temporal, or thematic proximity to the object of investigation, with the aim of ascribing meaning to that object. Such contextualizing may build on existing annotations and/or metadata.'),
	(41, 5, 'Modeling', 'Modeling is the activity of creating an abstract representation of a complex phenomenon, usually in a machine-readable way, possibly in an interactive way (i.e. it includes “simulation”). Models become machine-readable when modelling produces a schema that describes the elements and the structure of an object of inquiry in an explicit way. Modeling can also refer to the activity of transforming or manipulating a digital object in such a way as to make it compatible with a previously constructed model or schema. Mapping, for instance, is an example of a spatial model. Workflow design is included as part of Modeling, using an object such as Process.'),
	(42, 5, 'Theorizing', 'Theorizing is a method which aims to relate a number of elements or ideas into a coherent system based on some general principles and capable of explaining relevant phenomena or observations. Theorizing relies on techniques such as reasoning, abstract thinking, conceptualizing and defining. A theory may be implemented in the form of a model, or a model may give rise to formulating a theory.'),
	(43, 6, 'Archiving', 'Archiving includes the process of moving data and other resources to a separate space for retention. If long-term archiving is involved, activities related to data preservation may also be involved.'),
	(44, 6, 'Identifying', 'Identifying refers to the activity of naming and/or assigning (possibly unique and/or persistent) identifiers to objects of enquiry or to any kind of digital object. Adding a metadata description of the object is part of Annotation'),
	(45, 6, 'Organizing', 'Organizing refers to the arrangement of objects (research materials, data sets, images, etc.) in a way that facilitates other research activities. May also include activities that support discovery such as metadata creation and enhancement.'),
	(47, 6, 'Preservation', 'The application of specific strategies, activities and technologies for the purpose of ensuring an accurate rendering of digital content over time. It facilitates the reuse of research data, objects, and related resources and may include activities related to sustainability and interoperability. Related techniques include but are not limited to: Bit Stream Preservation, Durable Persistent Media, Emulation, Metadata Attachment, Migration, Replication, Technology Preservation, Versioning, the use of Open Archival Information Systems and standards that support interoperability.'),
	(48, 7, 'Collaboration', 'Collaboration is involved in any research activity being done jointly by several researchers, possibly in different places and at different times. Research-oriented collaboration is enabled, particularly, through comprehensive Digital Research Environments, but can also happen around more specific activities, such as communication or sharing of resources.'),
	(49, 7, 'Commenting', 'Commenting is the activity of adding information to a piece of data, usually in a way that separates the data to which the comment is attached and the comment. It usually serves to express some opinion, to add contextual information, or to engage in communication or collaboration with others about the object commented on. This is different from Annotating (as defined here) which refers to adding descriptive or explanatory information to sections of an object with the aim of making inherent qualities, structures, or meanings of that section explicit.'),
	(50, 7, 'Communicating', 'Communicating refers to the activity of exchanging ideas with other people, primarily, but not exclusively, using linguistic means. Relevant techniques include Email, Chat, Audio-Conferencing'),
	(51, 7, 'Crowdsourcing', 'Crowdsourcing refers to the paradigm of user-generated content in a web 2.0 context, applied here to the domain of digital humanities research. Crowdsourcing may include gamification, which may be understood as one form of creating motivation in crowdsourcing endeavors.'),
	(52, 7, 'Publishing', 'Publishing refers to the activity of making any kind of object formally available to the wider public. This can involve objects of research, research data, research results, or tools and services. Publishing can be closed or open access / open source, and research results can be published in print or digital formats.'),
	(53, 7, 'Sharing', 'Sharing refers to the activity of making objects publically available through informal channels such as blogs, code sharing sites such as GitHUB, or other social media sites.'),
	(54, 60, 'Assessing', 'Assessing refers to the activity of verifying the existence of certain properties, usually indicative of some desirable quality in some outcome of an activity. This may refer to reviewing research papers or conference proposals, to evaluating the coherence of the annotation of audio-visual materials, or to an assessment of the usefulness of the Digital Humanities.'),
	(55, 60, 'CommunityBuilding', 'Community building is the activity of creating or enhancing a community with a common interest. It may include dissemination, teaching as well as advocating for specific activities, practices, or values.'),
	(56, 60, 'GiveOverview', 'GiveOverview refers to the activity of providing information which is relatively general or provides a historical or systematic overview of a given topic. Nevertheless, it can be aimed at experts or beginners in a field, subfield or specialty.'),
	(57, 60, 'ProjectManagement', 'Project management involves activities such as developing a strategy and assessing risk for conducting a project, as well as task management activities, such as keeping a record of tasks, due dates, and other relevant information. It may include activities such as planning, documenting, getting funding, but also sending reminders and status reports. Project Management is related to Collaboration.'),
	(58, 60, 'Teaching/Learning', 'Teaching and Learning involves one group of people interactively helping another group of people acquire and/or develop skills, competencies, and knowledge that lets them solve problems in a specific area of research.'),
	(59, NULL, 'Research Activities', 'Research activities are usually applied to one or several research objects. An article about modeling of manuscript properties would therefore be tagged with the tags “modeling” and “manuscript”. A plain text editor would be tagged with the tags "writing" and "code" and "text". '),
	(60, NULL, 'Meta Activities', 'Meta Activities are activities which, unlike regular research activities, do not apply directly to a research object, but rather to a combination of a research activity with a research object. A case in point would be a tutorial "teaching" the digital "encoding" of "music", or a report "introducing" readers to "pattern_recognition" in "images". Meta-Activity tags can be added to provide additional context to a typical activity+object pair of tags. In some cases, however, meta-activities may also apply to objects, for example in the case of objects like “infrastructure” or “digital_humanities”. ');
/*!40000 ALTER TABLE `tadirah_activities` ENABLE KEYS */;