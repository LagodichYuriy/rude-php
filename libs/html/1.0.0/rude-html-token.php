<?

namespace rude;

# 8.2.4. Tokenization // https://w3c.github.io/html/syntax.html#tokenization
#
# The output of the tokenization step is a series of zero or more of the following
# tokens: DOCTYPE, start tag, end tag, comment, character, end-of-file. DOCTYPE to
# kens have a name, a public identifier, a system identifier, and a force-quirks f
# lag. When a DOCTYPE token is created, its name, public identifier, and system id
# entifier must be marked as missing (which is a distinct state from the empty str
# ing), and the force-quirks flag must be set to off (its other state is on). Star
# t and end tag tokens have a tag name, a self-closing flag, and a list of attribu
# tes, each of which has a name and a value. When a start or end tag token is crea
# ted, its self-closing flag must be unset (its other state is that it be set), an
# d its attributes list must be empty. Comment and character tokens have data.

class html_token
{

}