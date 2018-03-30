<?

namespace rude;

define('RUDE_HTML_STATE_DATA',                                           1);
define('RUDE_HTML_STATE_RCDATA',                                         2);
define('RUDE_HTML_STATE_RAWTEXT',                                        3);
define('RUDE_HTML_STATE_SCRIPT_DATA',                                    4);
define('RUDE_HTML_STATE_PLAINTEXT',                                      5);
define('RUDE_HTML_STATE_TAG_OPEN',                                       6);
define('RUDE_HTML_STATE_END_TAG_OPEN',                                   7);
define('RUDE_HTML_STATE_TAG_NAME',                                       8);
define('RUDE_HTML_STATE_RCDATA_LESS_THAN_SIGN',                          9);
define('RUDE_HTML_STATE_RCDATA_END_TAG_OPEN',                           10);
define('RUDE_HTML_STATE_RCDATA_END_TAG_NAME',                           11);
define('RUDE_HTML_STATE_RAWTEXT_LESS_THAN_SIGN',                        12);
define('RUDE_HTML_STATE_RAWTEXT_END_TAG_OPEN',                          13);
define('RUDE_HTML_STATE_RAWTEXT_END_TAG_NAME',                          14);
define('RUDE_HTML_STATE_SCRIPT_DATA_LESS_THAN_SIGN',                    15);
define('RUDE_HTML_STATE_SCRIPT_DATA_END_TAG_OPEN',                      16);
define('RUDE_HTML_STATE_SCRIPT_DATA_END_TAG_NAME',                      17);
define('RUDE_HTML_STATE_SCRIPT_DATA_ESCAPE_START',                      18);
define('RUDE_HTML_STATE_SCRIPT_DATA_ESCAPE_START_DASH',                 19);
define('RUDE_HTML_STATE_SCRIPT_DATA_ESCAPED',                           20);
define('RUDE_HTML_STATE_SCRIPT_DATA_ESCAPED_DASH',                      21);
define('RUDE_HTML_STATE_SCRIPT_DATA_ESCAPED_DASH_DASH',                 22);
define('RUDE_HTML_STATE_SCRIPT_DATA_ESCAPED_LESS_THAN_SIGN',            23);
define('RUDE_HTML_STATE_SCRIPT_DATA_ESCAPED_END_TAG_OPEN',              24);
define('RUDE_HTML_STATE_SCRIPT_DATA_ESCAPED_END_TAG_NAME',              25);
define('RUDE_HTML_STATE_SCRIPT_DATA_DOUBLE_ESCAPE_START',               26);
define('RUDE_HTML_STATE_SCRIPT_DATA_DOUBLE_ESCAPED',                    27);
define('RUDE_HTML_STATE_SCRIPT_DATA_DOUBLE_ESCAPED_DASH',               28);
define('RUDE_HTML_STATE_SCRIPT_DATA_DOUBLE_ESCAPED_DASH_DASH',          29);
define('RUDE_HTML_STATE_SCRIPT_DATA_DOUBLE_ESCAPED_LESS_THAN_SIGN',     30);
define('RUDE_HTML_STATE_SCRIPT_DATA_DOUBLE_ESCAPE_END',                 31);
define('RUDE_HTML_STATE_BEFORE_ATTRIBUTE_NAME',                         32);
define('RUDE_HTML_STATE_ATTRIBUTE_NAME',                                33);
define('RUDE_HTML_STATE_AFTER_ATTRIBUTE_NAME',                          34);
define('RUDE_HTML_STATE_BEFORE_ATTRIBUTE_VALUE',                        35);
define('RUDE_HTML_STATE_ATTRIBUTE_VALUE_DOUBLE_QUOTED',                 36);
define('RUDE_HTML_STATE_ATTRIBUTE_VALUE_SINGLE_QUOTED',                 37);
define('RUDE_HTML_STATE_ATTRIBUTE_VALUE_UNQUOTED',                      38);
define('RUDE_HTML_STATE_AFTER_ATTRIBUTE_VALUE_QUOTED',                  39);
define('RUDE_HTML_STATE_SELF_CLOSING_START_TAG',                        40);
define('RUDE_HTML_STATE_BOGUS_COMMENT',                                 41);
define('RUDE_HTML_STATE_MARKUP_DECLARATION_OPEN',                       42);
define('RUDE_HTML_STATE_COMMENT_START',                                 43);
define('RUDE_HTML_STATE_COMMENT_START_DASH',                            44);
define('RUDE_HTML_STATE_COMMENT',                                       45);
define('RUDE_HTML_STATE_COMMENT_LESS_THAN_SIGN',                        46);
define('RUDE_HTML_STATE_COMMENT_LESS_THAN_SIGN_BANG',                   47);
define('RUDE_HTML_STATE_COMMENT_LESS_THAN_SIGN_BANG_DASH',              48);
define('RUDE_HTML_STATE_COMMENT_LESS_THAN_SIGN_BANG_DASH_DASH',         49);
define('RUDE_HTML_STATE_COMMENT_END_DASH',                              50);
define('RUDE_HTML_STATE_COMMENT_END',                                   51);
define('RUDE_HTML_STATE_COMMENT_END_BANG',                              52);
define('RUDE_HTML_STATE_DOCTYPE',                                       53);
define('RUDE_HTML_STATE_BEFORE_DOCTYPE_NAME',                           54);
define('RUDE_HTML_STATE_DOCTYPE_NAME',                                  55);
define('RUDE_HTML_STATE_AFTER_DOCTYPE_NAME',                            56);
define('RUDE_HTML_STATE_AFTER_DOCTYPE_PUBLIC_KEYWORD',                  57);
define('RUDE_HTML_STATE_BEFORE_DOCTYPE_PUBLIC_IDENTIFIER',              58);
define('RUDE_HTML_STATE_DOCTYPE_PUBLIC_IDENTIFIER_DOUBLE_QUOTED',       59);
define('RUDE_HTML_STATE_DOCTYPE_PUBLIC_IDENTIFIER_SINGLE_QUOTED',       60);
define('RUDE_HTML_STATE_AFTER_DOCTYPE_PUBLIC_IDENTIFIER',               61);
define('RUDE_HTML_STATE_BETWEEN_DOCTYPE_PUBLIC_AND_SYSTEM_IDENTIFIERS', 62);
define('RUDE_HTML_STATE_AFTER_DOCTYPE_SYSTEM_KEYWORD',                  63);
define('RUDE_HTML_STATE_BEFORE_DOCTYPE_SYSTEM_IDENTIFIER',              64);
define('RUDE_HTML_STATE_DOCTYPE_SYSTEM_IDENTIFIER_DOUBLE_QUOTED',       65);
define('RUDE_HTML_STATE_DOCTYPE_SYSTEM_IDENTIFIER_SINGLE_QUOTED',       66);
define('RUDE_HTML_STATE_AFTER_DOCTYPE_SYSTEM_IDENTIFIER',               67);
define('RUDE_HTML_STATE_BOGUS_DOCTYPE',                                 68);
define('RUDE_HTML_STATE_CDATA_SECTION',                                 69);
define('RUDE_HTML_STATE_CDATA_SECTION_BRACKET',                         70);
define('RUDE_HTML_STATE_CDATA_SECTION_END',                             71);
define('RUDE_HTML_STATE_CHARACTER_REFERENCE',                           72);
define('RUDE_HTML_STATE_NUMERIC_CHARACTER_REFERENCE',                   73);
define('RUDE_HTML_STATE_HEXADECIMAL_CHARACTER_REFERENCE_START',         74);
define('RUDE_HTML_STATE_DECIMAL_CHARACTER_REFERENCE_START',             75);
define('RUDE_HTML_STATE_HEXADECIMAL_CHARACTER_REFERENCE',               76);
define('RUDE_HTML_STATE_DECIMAL_CHARACTER_REFERENCE',                   77);
define('RUDE_HTML_STATE_NUMERIC_CHARACTER_REFERENCE_END',               78);
define('RUDE_HTML_STATE_CHARACTER_REFERENCE_END',                       79);


define('RUDE_HTML_CHAR_NULL',            "\0");
define('RUDE_HTML_CHAR_EOF',             'EOF');
define('RUDE_HTML_CHAR_REPLACEMENT',     '�');
define('RUDE_HTML_CHAR_SOLIDUS',         '/');
define('RUDE_HTML_CHAR_LESS_THAN_SIGN',  '<');
define('RUDE_HTML_CHAR_SPACE',           ' ');
define('RUDE_HTML_CHAR_TAB',             0x09);
define('RUDE_HTML_CHAR_LINE_FEED',       0x0A);
define('RUDE_HTML_CHAR_FORM_FEED',       0x0C);
define('RUDE_HTML_CHAR_CARRIAGE_RETURN', 0x0D);

define('RUDE_HTML_OPTION_CONSUME', 1);
define('RUDE_HTML_OPTION_CONSUME_VALUE_NEXT', 1);

define('RUDE_HTML_OPTION_DEFAULT', 2);
define('RUDE_HTML_OPTION_DEFAULT_VALUE_EMIT', 1);

define('RUDE_HTML_OPTION_CHARS', 3);

define('RUDE_HTML_ACTION_STATE',                  1);
define('RUDE_HTML_ACTION_STATE_RETURN',           2);
define('RUDE_HTML_ACTION_PARSE_ERROR',            3);
define('RUDE_HTML_ACTION_EMIT',                   4);
define('RUDE_HTML_ACTION_EMIT_CURRENT_CHAR',      5);
define('RUDE_HTML_ACTION_EMIT_CURRENT_TAG',       6);
define('RUDE_HTML_ACTION_RECONSUME',              9);
define('RUDE_HTML_ACTION_CREATE',                10);
define('RUDE_HTML_ACTION_ASCII_UPPERCASE',       11);
define('RUDE_HTML_ACTION_ASCII_LOWERCASE',       12);

define('RUDE_HTML_CREATE_TAG',     1);
define('RUDE_HTML_CREATE_TAG_END', 2);
define('RUDE_HTML_CREATE_COMMENT', 3);



class html_tokenizer
{
	# http://w3c.github.io/html/syntax.html#writing-html-documents-elements


	private $html;

	private $chars = [];
	private $chars_amount = 0;

	private $char;

	private $offset = 0;

//	private $elements_special = [];
//	private $elements_formatting = [];

	private $data_states = [];

//	private $elements_void      = [];
//	private $elements_raw       = [];
//	private $elements_escapable = [];

	private $state;
	private $state_return;


	/** @var html_ascii */
	private $ascii;
	
	/** @var html_tokens */
	private $tokens;


	public function __construct($html)
	{
		# 8.2.2.5. Preprocessing the input stream
		#
		# https://w3c.github.io/html/syntax.html#preprocessing-the-input-stream
		#
		# U+000D CARRIAGE RETURN (CR) characters and U+000A LINE FEED (LF) characters are treated specially. Any LF character
		# that immediately follows a CR character must be ignored, and all CR characters must then be converted to LF charact
		# ers. Thus, newlines in HTML DOMs are represented by LF characters, and there are never any CR characters in the inp
		# ut to the tokenization stage.

		$this->html = strings::replace($html, [RUDE_HTML_CHAR_LINE_FEED . RUDE_HTML_CHAR_CARRIAGE_RETURN, RUDE_HTML_CHAR_CARRIAGE_RETURN], RUDE_HTML_CHAR_LINE_FEED);

		$this->chars = strings::chars($this->html);
		$this->chars[] = RUDE_HTML_CHAR_EOF;

		$this->chars_amount = count($this->chars);


		
		$this->ascii = new html_ascii();
		$this->tokens = new html_tokens();
		

//
//		$this->elements_void      = array_flip(['area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr']);
//		$this->elements_raw       = array_flip(['script', 'style']);
//		$this->elements_escapable = array_flip(['textarea', 'title']);


		# http://w3c.github.io/html/syntax.html#special

//		$this->elements_special   = array_flip(['area', 'article', 'aside', 'base', 'basefont', 'bgsound', 'blockquote', 'body', 'br', 'button', 'caption', 'center', 'col', 'colgroup', 'dd', 'details', 'dir', 'div', 'dl', 'dt', 'embed', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hr', 'html', 'iframe', 'img', 'input', 'li', 'link', 'listing', 'main', 'marquee', 'meta', 'nav', 'noembed', 'noframes', 'noscript', 'object', 'ol', 'p', 'param', 'plaintext', 'pre', 'script', 'section', 'select', 'source', 'style', 'summary', 'table', 'tbody', 'td', 'template', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'track', 'ul', 'wbr', 'xmp', 'mi', 'mo', 'mn', 'ms', 'mtext', 'annotation-xml', 'foreignObject', 'desc', 'title']);


		# http://w3c.github.io/html/syntax.html#formatting

//		$this->elements_formatting = array_flip(['']);


//		$this->states_special = array_flip(['consume', 'default']);

		# http://w3c.github.io/html/syntax.html#tokenizer-data-state


		
	}

	public function parse()
	{
		# http://w3c.github.io/html/syntax.html#data-state

		# The state machine must start in the data state.

		static::state(RUDE_HTML_STATE_DATA);

		
		$this->offset = 0;
		
		while ($this->offset < $this->chars_amount)
		{
			console::write(console::colorize($this->state, false, 'yellow'));

			static::tokenize();
		}
	}

	private function tokenize()
	{
		#######################################################
		# https://w3c.github.io/html/syntax.html#tokenization #

		switch ($this->state)
		{
			# 8.2.4.1. Data state
			#
			# Consume the next input character:
			# ------------------------------------------------------------------------------------------------------------
			# U+0026 AMPERSAND (&)      | Set the return state to the data state. Switch to the character reference state.
			# U+003C LESS-THAN SIGN (<) | Switch to the tag open state.
			# U+0000 NULL               | Parse error. Emit the current input character as a character token.
			# EOF                       | Emit an end-of-file token.
			# Anything else             | Emit the current input character as a character token.

			case RUDE_HTML_STATE_DATA:

				static::char_consume();

				switch ($this->char)
				{
					case '&': static::state(RUDE_HTML_STATE_CHARACTER_REFERENCE, RUDE_HTML_STATE_DATA); break;
					case '<': static::state(RUDE_HTML_STATE_TAG_OPEN);                                  break;

					case RUDE_HTML_CHAR_NULL: static::parse_error(); static::emit_char_current(); break;
					case RUDE_HTML_CHAR_EOF:  static::create_token_eof();                                 break;

					default: static::emit_char_current();
				}

				break;


			# 8.2.4.2. RCDATA state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------
			# U+0026 AMPERSAND (&)      | Set the return state to the RCDATA state. Switch to the character reference state.
			# U+003C LESS-THAN SIGN (<) | Switch to the RCDATA less-than sign state.
			# U+0000 NULL               | Parse error. Emit a U+FFFD REPLACEMENT CHARACTER character token.
			# EOF                       | Emit an end-of-file token.
			# Anything else             | Emit the current input character as a character token.

			case RUDE_HTML_STATE_RCDATA:

				static::char_consume();

				switch ($this->char)
				{
					case '&': static::state(RUDE_HTML_STATE_CHARACTER_REFERENCE, RUDE_HTML_STATE_RCDATA); break;
					case '<': static::state(RUDE_HTML_STATE_RCDATA_LESS_THAN_SIGN);                       break;

					case RUDE_HTML_CHAR_NULL: static::parse_error(); static::emit_char_replacement(); break;
					case RUDE_HTML_CHAR_EOF:  static::create_token_eof();                                     break;

					default: static::emit_char_current();
				}

				break;


			# 8.2.4.3. RAWTEXT state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------
			# U+003C LESS-THAN SIGN (<) | Switch to the RAWTEXT less-than sign state.
			# U+0000 NULL               | Parse error. Emit a U+FFFD REPLACEMENT CHARACTER character token.
			# EOF                       | Emit an end-of-file token.
			# Anything else             | Emit the current input character as a character token.

			case RUDE_HTML_STATE_RAWTEXT:

				static::char_consume();

				switch ($this->char)
				{
					case '<': static::state(RUDE_HTML_STATE_RAWTEXT_LESS_THAN_SIGN); break;

					case RUDE_HTML_CHAR_NULL: static::parse_error(); static::emit_char_replacement(); break;
					case RUDE_HTML_CHAR_EOF:  static::create_token_eof();                                     break;

					default: static::emit_char_current();
				}

				break;


			# 8.2.4.4. Script data state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------
			# U+003C LESS-THAN SIGN (<) | Switch to the script data less-than sign state.
			# U+0000 NULL               | Parse error. Emit a U+FFFD REPLACEMENT CHARACTER character token.
			# EOF                       | Emit an end-of-file token.
			# Anything else             | Emit the current input character as a character token.

			case RUDE_HTML_STATE_SCRIPT_DATA:

				static::char_consume();

				switch ($this->char)
				{
					case '<': static::state(RUDE_HTML_STATE_SCRIPT_DATA_LESS_THAN_SIGN); break;

					case RUDE_HTML_CHAR_NULL: static::parse_error(); static::emit_char_replacement(); break;
					case RUDE_HTML_CHAR_EOF:  static::create_token_eof();                                     break;

					default: static::emit_char_current();
				}

				break;


			# 8.2.4.5. PLAINTEXT state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------
			# U+0000 NULL   Parse error. Emit a U+FFFD REPLACEMENT CHARACTER character token.
			# EOF           Emit an end-of-file token.
			# Anything else Emit the current input character as a character token.

			case RUDE_HTML_STATE_PLAINTEXT:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_NULL: static::parse_error(); static::emit_char_replacement(); break;
					case RUDE_HTML_CHAR_EOF:  static::create_token_eof();                                     break;

					default: static::emit_char_current();
				}

				break;


			# 8.2.4.6. Tag open state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# U+0021 EXCLAMATION MARK (!) | Switch to the markup declaration open state.
			# U+002F SOLIDUS (/)          | Switch to the end tag open state.
			# ASCII letter                | Create a new start tag token, set its tag name to the empty string. Reconsume in the tag name state.
			# U+003F QUESTION MARK (?)    | Parse error. Create a comment token whose data is the empty string. Reconsume in the bogus comment state.
			# Anything else               | Parse error. Emit a U+003C LESS-THAN SIGN character token. Reconsume in the data state.

			case RUDE_HTML_STATE_TAG_OPEN:

				static::char_consume();

				switch ($this->char)
				{
					case '!': static::state(RUDE_HTML_STATE_MARKUP_DECLARATION_OPEN); break;
					case '/': static::state(RUDE_HTML_STATE_END_TAG_OPEN);            break;

					case '?':
						static::parse_error();
						static::create_comment();
						static::state_reconsume(RUDE_HTML_STATE_BOGUS_COMMENT);
						break;

					default:

						if (isset($this->ascii_alphabetic[$this->char]))
						{
							static::create_tag_start();

							console::log('name to empty string', RUDE_E_SUCCESS);
							# set its tag name to the empty string
							static::state_reconsume(RUDE_HTML_STATE_TAG_NAME);
						}
						else
						{
							static::parse_error();
							static::emit_char_less_than_sign();
							static::state_reconsume(RUDE_HTML_STATE_DATA);
						}
				}

				break;


			# 8.2.4.7. End tag open state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# ASCII letter                 | Create a new end tag token, set its tag name to the empty string. Reconsume in the tag name state.
			# U+003E GREATER-THAN SIGN (>) | Parse error. Switch to the data state.
			# EOF                          | Parse error. Emit a U+003C LESS-THAN SIGN character token, a U+002F SOLIDUS character token and an end-of-file token.
			# Anything else                | Parse error. Create a comment token whose data is the empty string. Reconsume in the bogus comment state.

			case RUDE_HTML_STATE_END_TAG_OPEN:

				static::char_consume();

				switch ($this->char)
				{
					case '>':
						static::parse_error();
						static::state(RUDE_HTML_STATE_DATA);
						break;

					case RUDE_HTML_CHAR_EOF:
						static::parse_error();
						static::emit_char_less_than_sign();
						static::emit_char_solidus();
						static::create_token_eof();
						break;

					default:

						if (isset($this->ascii_alphabetic[$this->char]))
						{
							static::create_tag_end();
							# set its tag name to the empty string
							static::state_reconsume(RUDE_HTML_STATE_TAG_NAME);
						}
						else
						{
							static::parse_error();
							static::create_comment();
							static::state_reconsume(RUDE_HTML_STATE_BOGUS_COMMENT);
						}
				}

				break;


			# 8.2.4.8. Tag name state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# U+0009 CHARACTER TABULATION (tab) |
			# U+000A LINE FEED (LF)             |
			# U+000C FORM FEED (FF)             |
			# U+0020 SPACE                      | Switch to the before attribute name state.
			# U+002F SOLIDUS (/)                | Switch to the self-closing start tag state.
			# U+003E GREATER-THAN SIGN (>)      | Switch to the data state. Emit the current tag token.
			# Uppercase ASCII letter            | Append the lowercase version of the current input character (add 0x0020 to the character’s code point) to the current tag token’s tag name.
			# U+0000 NULL                       | Parse error. Append a U+FFFD REPLACEMENT CHARACTER character to the current tag token’s tag name.
			# EOF                               | Parse error. Emit an end-of-file token.
			# Anything else                     | Append the current input character to the current tag token’s tag name.

			case RUDE_HTML_STATE_TAG_NAME:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_TAB:
					case RUDE_HTML_CHAR_LINE_FEED:
					case RUDE_HTML_CHAR_FORM_FEED:
					case RUDE_HTML_CHAR_SPACE:
						static::state(RUDE_HTML_STATE_BEFORE_ATTRIBUTE_NAME);
						break;

					case '/':
						static::state(RUDE_HTML_STATE_SELF_CLOSING_START_TAG);
						break;

					case '>':
						static::state(RUDE_HTML_STATE_DATA);
						static::emit_tag(); # Emit the current tag token.
						break;

					case RUDE_HTML_CHAR_NULL:
						static::parse_error();

						$this->tokens->token->name .= RUDE_HTML_CHAR_REPLACEMENT;
						break;

					case RUDE_HTML_CHAR_EOF:
						static::parse_error();
						static::create_token_eof();
						break;

					default:
						$this->tokens->token->name .= $this->ascii->to_lowercase($this->char);
				}

				break;

			# 8.2.4.9. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.10. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.11. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.12. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.13. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.14. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.15. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.16. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.17. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.18. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.19. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.20. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.21. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.22. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.23. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.24. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.25. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.26. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.27. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.28. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.29. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.30. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.31. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------


			# 8.2.4.32. Before attribute name state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# U+0009 CHARACTER TABULATION (tab) |
			# U+000A LINE FEED (LF)             |
			# U+000C FORM FEED (FF)             |
			# U+0020 SPACE                      | Ignore the character.
			# U+002F SOLIDUS (/)                |
			# U+003E GREATER-THAN SIGN (>)      |
			# EOF                               | Reconsume in the after attribute name state.
			# U+003D EQUALS SIGN (=)            | Parse error. Start a new attribute in the current tag token. Set that attribute’s name to the current input character, and its value to the empty string. Switch to the attribute name state.
			# Anything else                     | Start a new attribute in the current tag token. Set that attribute’s name and value to the empty string. Reconsume in the attribute name state.

			case RUDE_HTML_STATE_BEFORE_ATTRIBUTE_NAME:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_TAB:
					case RUDE_HTML_CHAR_LINE_FEED:
					case RUDE_HTML_CHAR_FORM_FEED:
					case RUDE_HTML_CHAR_SPACE:
						# Ignore the character
						break;

					case '/':
					case '>':
					case RUDE_HTML_CHAR_EOF:
						static::state_reconsume(RUDE_HTML_STATE_AFTER_ATTRIBUTE_NAME);
						break;

					case '=':
						static::parse_error();

						# Start a new attribute in the current tag token.
						# Set that attribute’s name to the current input character, and its value to the empty string.

						static::state(RUDE_HTML_STATE_ATTRIBUTE_NAME);

						break;

					default:
						# Start a new attribute in the current tag token
						# Set that attribute’s name and value to the empty string

						static::state_reconsume(RUDE_HTML_STATE_ATTRIBUTE_NAME);
				}

				break;


			# 8.2.4.33. Attribute name state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# U+0009 CHARACTER TABULATION (tab) |
			# U+000A LINE FEED (LF)             |
			# U+000C FORM FEED (FF)             |
			# U+0020 SPACE                      |
			# U+002F SOLIDUS (/)                |
			# U+003E GREATER-THAN SIGN (>)      |
			# EOF                               | Reconsume in the after attribute name state.
			# U+003D EQUALS SIGN (=)            | Switch to the before attribute value state.
			# Uppercase ASCII letter            | Append the lowercase version of the current input character (add 0x0020 to the character’s code point) to the current attribute’s name.
			# U+0000 NULL                       | Parse error. Append a U+FFFD REPLACEMENT CHARACTER character to the current attribute’s name.
			# U+0022 QUOTATION MARK (")         |
			# U+0027 APOSTROPHE (')             |
			# U+003C LESS-THAN SIGN (<)         | Parse error. Treat it as per the "anything else" entry below.
			# Anything else                     | Append the current input character to the current attribute’s name.

			case RUDE_HTML_STATE_ATTRIBUTE_NAME:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_TAB:
					case RUDE_HTML_CHAR_LINE_FEED:
					case RUDE_HTML_CHAR_FORM_FEED:
					case RUDE_HTML_CHAR_SPACE:
					case RUDE_HTML_CHAR_EOF:
					case '/':
					case '>':
						static::state_reconsume(RUDE_HTML_STATE_AFTER_ATTRIBUTE_NAME);
						break;

					case '=':
						static::state(RUDE_HTML_STATE_BEFORE_ATTRIBUTE_VALUE);
						break;

					case RUDE_HTML_CHAR_NULL:
						static::parse_error();

						# Append a U+FFFD REPLACEMENT CHARACTER character to the current attribute’s name.
						break;

					case '<':
					case '"':
					case "'":
						static::parse_error();

						# Parse error.
						# Append the current input character to the current attribute’s name.

						break;

					default:

						if (static::ascii_is_uppercase())
						{
							# Append the lowercase version of the current input character (add 0x0020 to the character’s code point) to the current attribute’s name.
						}
						else
						{
							# Append the current input character to the current attribute’s name.
						}
				}

				break;


			# 8.2.4.34. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------


			# 8.2.4.35. Before attribute value state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# U+0009 CHARACTER TABULATION (tab)
			# U+000A LINE FEED (LF)
			# U+000C FORM FEED (FF)
			# U+0020 SPACE                     Ignore the character.
			# U+0022 QUOTATION MARK (")        Switch to the attribute value (double-quoted) state.
			# U+0027 APOSTROPHE (')            Switch to the attribute value (single-quoted) state.
			# U+003E GREATER-THAN SIGN (>)     Parse error. Treat it as per the "anything else" entry below.
			# Anything else                    Reconsume in the attribute value (unquoted) state.

			case RUDE_HTML_STATE_BEFORE_ATTRIBUTE_VALUE:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_TAB:
					case RUDE_HTML_CHAR_LINE_FEED:
					case RUDE_HTML_CHAR_FORM_FEED:
					case RUDE_HTML_CHAR_SPACE:
						# Ignore the character
						break;

					case '"':
						static::state_reconsume(RUDE_HTML_STATE_ATTRIBUTE_VALUE_DOUBLE_QUOTED);
						break;

					case "'":
						static::state_reconsume(RUDE_HTML_STATE_ATTRIBUTE_VALUE_SINGLE_QUOTED);
						break;

					case '>':
						static::parse_error();
						static::state_reconsume(RUDE_HTML_STATE_ATTRIBUTE_VALUE_UNQUOTED);
						break;

					default:
						static::state_reconsume(RUDE_HTML_STATE_ATTRIBUTE_VALUE_UNQUOTED);
				}

				break;


			# 8.2.4.36. Attribute value (double-quoted) state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------
			# U+0022 QUOTATION MARK (") Switch to the after attribute value (quoted) state.
			# U+0026 AMPERSAND (&)      Set the return state to the attribute value (double-quoted) state. Switch to the character reference state.
			# U+0000 NULL               Parse error. Append a U+FFFD REPLACEMENT CHARACTER character to the current attribute’s value.
			# EOF                       Parse error. Emit an end-of-file token.
			# Anything else             Append the current input character to the current attribute’s value.

			case RUDE_HTML_STATE_ATTRIBUTE_VALUE_DOUBLE_QUOTED:

				static::char_consume();

				switch ($this->char)
				{
					case '"': static::state(RUDE_HTML_STATE_AFTER_ATTRIBUTE_VALUE_QUOTED); break;
					case '&': static::state(RUDE_HTML_STATE_CHARACTER_REFERENCE, RUDE_HTML_STATE_ATTRIBUTE_VALUE_DOUBLE_QUOTED); break;

					case RUDE_HTML_CHAR_NULL:
						static::parse_error();
						# Append a U+FFFD REPLACEMENT CHARACTER character to the current attribute’s value
						break;

					case RUDE_HTML_CHAR_EOF:
						static::parse_error();
						static::create_token_eof();
						break;

					default:
						# Append the current input character to the current attribute’s value.
				}

				break;


			# 8.2.4.37. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.38. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.39. After attribute value (quoted) state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------
			# U+0009 CHARACTER TABULATION (tab) |
			# U+000A LINE FEED (LF)             |
			# U+000C FORM FEED (FF)             |
			# U+0020 SPACE                      | Switch to the before attribute name state.
			# U+002F SOLIDUS (/)                | Switch to the self-closing start tag state.
			# U+003E GREATER-THAN SIGN (>)      | Switch to the data state. Emit the current tag token.
			# EOF                               | Parse error. Emit an end-of-file token.
			# Anything else                     | Parse error. Reconsume in the before attribute name state.

			case RUDE_HTML_STATE_AFTER_ATTRIBUTE_VALUE_QUOTED:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_TAB:
					case RUDE_HTML_CHAR_LINE_FEED:
					case RUDE_HTML_CHAR_FORM_FEED:
					case RUDE_HTML_CHAR_SPACE:
						static::state(RUDE_HTML_STATE_BEFORE_ATTRIBUTE_NAME);
						break;

					case '/':
						static::state(RUDE_HTML_STATE_SELF_CLOSING_START_TAG);
						break;

					case '>':
						static::state(RUDE_HTML_STATE_DATA);
						static::emit_char_current();
						break;

					case RUDE_HTML_CHAR_EOF:
						static::parse_error();
						static::create_token_eof();
						break;

					default:
						static::parse_error();
						static::state_reconsume(RUDE_HTML_STATE_ATTRIBUTE_NAME);
				}

				break;


			# 8.2.4.40. Self-closing start tag state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------
			# U+003E GREATER-THAN SIGN (>) | Set the self-closing flag of the current tag token. Switch to the data state. Emit the current tag token.
			# EOF                          | Parse error. Emit an end-of-file token.
			# Anything else                | Parse error. Reconsume in the before attribute name state.

			case RUDE_HTML_STATE_SELF_CLOSING_START_TAG:

				static::char_consume();

				switch ($this->chars)
				{
					case '>':
						$this->tokens->token->flags['self-closing'] = true;
						$this->tokens->emit();

						static::state(RUDE_HTML_STATE_DATA);
						break;

					case RUDE_HTML_CHAR_EOF:
						static::parse_error();

						$this->tokens->emit_eof();
						break;

					default:
						static::parse_error();

						static::state_reconsume(RUDE_HTML_STATE_ATTRIBUTE_NAME);
				}

				break;


			# 8.2.4.41. Bogus comment state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------
			# U+003E GREATER-THAN SIGN (>) | Switch to the data state. Emit the comment token.
			# EOF Emit the comment.        | Emit an end-of-file token.
			# U+0000 NULL                  | Append a U+FFFD REPLACEMENT CHARACTER character to the comment token’s data.
			# Anything else                | Append the current input character to the comment token’s data.

			case RUDE_HTML_STATE_BOGUS_COMMENT:

				static::char_consume();

				switch ($this->char)
				{
					case '>':
						$this->tokens->emit_comment();

						static::state(RUDE_HTML_STATE_DATA);
						break;

					case RUDE_HTML_CHAR_EOF:
						$this->tokens->emit_eof();
						break;

					case RUDE_HTML_CHAR_NULL:
						$this->tokens->token->comment .= RUDE_HTML_CHAR_REPLACEMENT;
						break;

					default:
						$this->tokens->token->comment .= $this->char;
				}

				break;


			# 8.2.4.42. Markup declaration open state
			#
			# If the next two characters are both U+002D HYPHEN-MINUS characters (-), consume those two characters, create a comment token whose data is the empty string, and switch to the comment start state.
			# Otherwise, if the next seven characters are an ASCII case-insensitive match for the word "DOCTYPE", then consume those characters and switch to the DOCTYPE state.
			# Otherwise, if there is an adjusted current node and it is not an element in the HTML namespace and the next seven characters are a case-sensitive match for the string "[CDATA[" (the five uppercase letters "CDATA" with a U+005B LEFT SQUARE BRACKET character before and after), then consume those characters and switch to the CDATA section state.
			# Otherwise, this is a parse error. Create a comment token whose data is the empty string. Switch to the bogus comment state (don’t consume anything in the current state).

			case RUDE_HTML_STATE_MARKUP_DECLARATION_OPEN:

				if (static::is_next('--', 2))
				{
					static::chars_consume(2);

					$this->tokens->create('comment');
					$this->tokens->token->data = '';

					static::state(RUDE_HTML_STATE_COMMENT_START);
				}
				else if (static::is_next('DOCTYPE', 7))
				{
					static::chars_consume(7);
					static::state(RUDE_HTML_STATE_DOCTYPE);
				}
				else if (static::is_next('[CDATA[', 7, true))
				{
					static::chars_consume(7);
					static::state(RUDE_HTML_STATE_CDATA_SECTION);
				}
				else
				{
					static::parse_error();

					$this->token = static::token_create('comment');
					$this->token->comment;

					static::state(RUDE_HTML_STATE_BOGUS_COMMENT);
				}

				break;


			# 8.2.4.43. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.44. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.45. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.46. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.47. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.48. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.49. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.50. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.51. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.52. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------

			# 8.2.4.53. DOCTYPE state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# U+0009 CHARACTER TABULATION (tab) |
			# U+000A LINE FEED (LF)             |
			# U+000C FORM FEED (FF)             |
			# U+0020 SPACE                      | Switch to the before DOCTYPE name state.
			# EOF                               | Parse error. Create a new DOCTYPE token. Set its force-quirks flag to on. Emit the token. Emit an end-of-file token.
			# Anything else                     | Parse error. Reconsume in the before DOCTYPE name state.

			case RUDE_HTML_STATE_DOCTYPE:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_TAB:
					case RUDE_HTML_CHAR_LINE_FEED:
					case RUDE_HTML_CHAR_FORM_FEED:
					case RUDE_HTML_CHAR_SPACE:
						static::state(RUDE_HTML_STATE_BEFORE_DOCTYPE_NAME);
						break;

					case RUDE_HTML_CHAR_EOF:
						static::parse_error();

						# Create a new DOCTYPE token
						# Set its force-quirks flag to on
						# Emit the token
						# Emit an end-of-file token

						break;

					default:
						static::parse_error();
						static::state_reconsume(RUDE_HTML_STATE_DOCTYPE_NAME);
				}

				break;


			# 8.2.4.54. Before DOCTYPE name state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# U+0009 CHARACTER TABULATION (tab) |
			# U+000A LINE FEED (LF)             |
			# U+000C FORM FEED (FF)             |
			# U+0020 SPACE                      | Ignore the character.
			# Uppercase ASCII letter            | Create a new DOCTYPE token. Set the token’s name to the lowercase version of the current input character (add 0x0020 to the character’s code point). Switch to the DOCTYPE name state.
			# U+0000 NULL                       | Parse error. Create a new DOCTYPE token. Set the token’s name to a U+FFFD REPLACEMENT CHARACTER character. Switch to the DOCTYPE name state.
			# U+003E GREATER-THAN SIGN (>)      | Parse error. Create a new DOCTYPE token. Set its force-quirks flag to on. Switch to the data state. Emit the token.
			# EOF                               | Parse error. Create a new DOCTYPE token. Set its force-quirks flag to on. Emit the token. Emit an end-of-file token.
			# Anything else                     | Create a new DOCTYPE token. Set the token’s name to the current input character. Switch to the DOCTYPE name state.

			case RUDE_HTML_STATE_BEFORE_DOCTYPE_NAME:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_TAB:
					case RUDE_HTML_CHAR_LINE_FEED:
					case RUDE_HTML_CHAR_FORM_FEED:
					case RUDE_HTML_CHAR_SPACE:
						break;

					case RUDE_HTML_CHAR_NULL:
						static::parse_error();

						$this->tokens->create('DOCTYPE');
						$this->tokens->token->name = RUDE_HTML_CHAR_REPLACEMENT;

						static::state(RUDE_HTML_STATE_DOCTYPE_NAME);
						break;

					case '>':

						static::parse_error();

						$this->tokens->create('DOCTYPE');
						$this->tokens->token->flags['force-quirks'] = true;
						$this->tokens->emit();

						static::state(RUDE_HTML_STATE_DATA);
						break;

					case RUDE_HTML_CHAR_EOF:

						static::parse_error();

						$this->tokens->create('DOCTYPE');
						$this->tokens->token->flags['force-quirks'] = true;
						$this->tokens->emit();
						$this->tokens->emit_eof();
						break;

					default:
						$this->tokens->create('DOCTYPE');
						$this->tokens->token->name = $this->ascii->to_lowercase($this->char);

						static::state(RUDE_HTML_STATE_DOCTYPE_NAME);
				}

				break;


			# 8.2.4.55. DOCTYPE name state
			#
			# Consume the next input character:
			# ---------------------------------------------------------------------------------------------------------------------------------------
			# U+0009 CHARACTER TABULATION (tab) |
			# U+000A LINE FEED (LF)             |
			# U+000C FORM FEED (FF)             |
			# U+0020 SPACE                      | Switch to the after DOCTYPE name state.
			# U+003E GREATER-THAN SIGN (>)      | Switch to the data state. Emit the current DOCTYPE token.
			# Uppercase ASCII letter            | Append the lowercase version of the current input character (add 0x0020 to the character’s code point) to the current DOCTYPE token’s name.
			# U+0000 NULL                       | Parse error. Append a U+FFFD REPLACEMENT CHARACTER character to the current DOCTYPE token’s name.
			# EOF                               | Parse error. Set the DOCTYPE token’s force-quirks flag to on. Emit that DOCTYPE token. Emit an end-of-file token.
			# Anything else                     | Append the current input character to the current DOCTYPE token’s name.

			case RUDE_HTML_STATE_DOCTYPE_NAME:

				static::char_consume();

				switch ($this->char)
				{
					case RUDE_HTML_CHAR_TAB:
					case RUDE_HTML_CHAR_LINE_FEED:
					case RUDE_HTML_CHAR_FORM_FEED:
					case RUDE_HTML_CHAR_SPACE:
						static::state(RUDE_HTML_STATE_AFTER_DOCTYPE_NAME);
						break;

					case '>':
						$this->tokens->token->name .= RUDE_HTML_CHAR_REPLACEMENT;
						$this->tokens->emit();

						static::state(RUDE_HTML_STATE_DATA);
						break;

					case RUDE_HTML_CHAR_NULL:
						static::parse_error();

						$this->tokens->token->name .= RUDE_HTML_CHAR_REPLACEMENT;
						break;

					case RUDE_HTML_CHAR_EOF:
						$this->tokens->token->flags['force-quirks'] = true;
						$this->tokens->emit();
						$this->tokens->emit_eof();
						break;

					default:
						$this->tokens->token->name .= $this->ascii->to_lowercase($this->char);
				}

				break;


			# 8.2.4.56. %%%%%% state
			#
			# Consume the next input character:
			# --------------------------------------------------------------------------------------------------------------


			default:

				exception::error("unknown state: $this->state");

				break;
		}
	}

	private function is_next($substring, $length = null, $case_insensetive = false)
	{
		if ($length === null)
		{
			$length = strlen($substring);
		}


		$limit = $this->offset + $length;

		if (!isset($this->chars[$limit]))
		{
			return false;
		}


		$string = '';

		for ($i = $this->offset; $i < $limit; $i++)
		{
			$string .= $this->chars[$i];
		}

		if ($case_insensetive === false)
		{
			return strcasecmp($string, $substring) === 0;
		}

		return strcmp($string, $substring) === 0;
	}



	private function char_consume()
	{
		$this->char = $this->chars[$this->offset];

		debug($this->char, true);

		$this->offset++;
	}

	private function chars_consume($chars)
	{
		for ($i = 0; $i < $chars; $i++)
		{
			static::char_consume();
		}
	}

	private function char_reconsume()
	{
		$this->offset--;
	}

	private function create_token_eof()         { static::token_create('EOF');                      }
	private function emit_char_solidus()        { static::emit_char(RUDE_HTML_CHAR_SOLIDUS);        }
	private function emit_char_replacement()    { static::emit_char(RUDE_HTML_CHAR_REPLACEMENT);    }
	private function emit_char_less_than_sign() { static::emit_char(RUDE_HTML_CHAR_LESS_THAN_SIGN); }

	private function emit_tag() { }

	private function emit_char_current() { static::emit_char($this->char); }

	private function emit_char($char)
	{
		debug($char, true);
	}

	private function create_tag_start()
	{
		console::log('tag opened', RUDE_E_SUCCESS);
		debug('<');
	}

	private function create_tag_end()
	{
		console::log('tag closed', RUDE_E_SUCCESS);
		debug('>');
	}

	private function create_comment()
	{
		debug('*comment*');
	}

	private function state_reconsume($state)
	{
		static::char_reconsume();
		static::state($state);
	}

	private function state($state, $state_return = null)
	{
		$this->state = $state;

		if ($state_return !== null)
		{
			static::state_return($state_return);
		}
	}

	private function state_return($value)
	{
		$this->state_return = $value;
	}

	private function parse_error()
	{
		exception::warning('parse error');
	}
}