test:
	ocamlbuild -pkgs oUnit test_part2.byte && ./test_part2.byte

check:
	bash checkenv.sh && bash checktypes.sh

clean:
	ocamlbuild -clean
	rm -f checktypes.ml
