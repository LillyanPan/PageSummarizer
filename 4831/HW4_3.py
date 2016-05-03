import networkx as nx
import matplotlib as mpl
mpl.use('Agg')
import matplotlib.pyplot as plt
from scipy.cluster.vq import whiten
import numpy as np
nx.draw(dmNet2, pos=nx.spring_layout(dmNet2))