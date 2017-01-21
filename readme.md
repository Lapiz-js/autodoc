## AutoDoc

A simple documentation generator.
```javascript
// > SomeFunction(arg1, arg2)
// > SomeFunction(different, signature, option)
// Lines
// of
// documentation
/* >
example();
code = 0;
*/
// more docs
SomeFunction(){
  ...
}
```

A doc-block always starts with "// >" followed by a segment of code
representingthe interface being documented. If there are multiple versions of
the interface all variations should be listed, each on their own line. All
successive comment lines (lines where the first non-white space characters are
//) will be part of the documentation and will be rendered as Markdown.

Example code can be inserted by starting a line with "/* >". A useful trick is
that changing this line to /* > */ will cause an editor to syntax highlight the
example.

When generating the Markdown, AutoDoc will generate an index at the top of the
document. It will attempt to nest the index correctly (so "Foo.bar" is a child
of "Foo" and a sibling to "Foo.glorp");

Currently, I am testing against github's markdown and [Markdown
Viewer](https://github.com/Thiht/markdown-viewer) to make sure linking works and
rendering looks good.