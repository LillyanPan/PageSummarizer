import networkx as nx
import matplotlib as mpl
mpl.use('Agg')
import matplotlib.pyplot as plt
from scipy.cluster.vq import whiten
import numpy as np

G = nx.Graph()


# Histgram, x is data
plt.hist(x, bins=40)